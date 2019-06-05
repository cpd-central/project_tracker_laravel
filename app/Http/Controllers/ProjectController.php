<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Project;
use MongoDB\BSON\UTCDateTime; 
use MongoDB\BSON\Decimal128; 

class ProjectController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }
  
  protected function store($project, $req)
  {
    $project->cegproposalauthor= $req->get('cegproposalauthor');
    $project->projectname= $req->get('projectname');
    $project->clientcontactname= $req->get('clientcontactname');
    $project->clientcompany = $req->get('clientcompany');
    $project->mwsize = $req->get('mwsize');
    $project->voltage = $req->get('voltage');
    $project->dollarvalueinhouse = $req->get('dollarvalueinhouse');
    $project->dateproposed = $req->get('dateproposed');
    $project->datentp = $req->get('datentp');
    $project->dateenergization = $req->get('dateenergization');
    $project->projecttype = $req->get('projecttype_checklist');
    $project->epctype = $req->get('epctype_checklist');
    $project->projectstatus = $req->get('projectstatus');
    $project->projectcode = $req->get('projectcode');
    $project->projectmanager = $req->get('projectmanager');
    $project->save();
  }

  protected function validate_request($req)
  {
    $this->validate($req, [
      'cegproposalauthor' => 'required',
      'projectname' => 'required',
      'clientcontactname' => 'required'
    ]);
  }

  public function new_project()
  {
    return view('pages.newproject');
  }


  public function create(Request $request)
  {
    $this->validate_request($request);
    $project = new Project();
    $this->store($project, $request);
    return redirect('/projectindex')->with('Success!', 'Project has been successfully added.');
  }

  public function update(Request $request, $id)
  {
    $this->validate_request($request);
    $project = Project::find($id);
    $this->store($project, $request);
    return redirect('/projectindex')->with('Success!', 'Project has been successfully updated');
  }

  public function index()
  {
    $projects=Project::all();
    return view('pages.projectindex', compact('projects'));
  }

  public function indexwon()
  {
    $maxenddate = 0;
    $MAXxDATE = 0;
    $today=time();
    $FIELDS = 5;
    $lastarray=array();
    $total_footer_array=array();
    $projects=Project::all()->where('projectstatus','Won');

    //Finding the energization date that is the furthest out... this sets the end of the table
    $averagePERmonthARRAYperPROJECT = array();
    foreach($projects as $project)
    {
      $enddate=new UTCDateTime(strtotime($project['dateenergization']));
      $enddate=$enddate->toDateTime();
      $enddate=$enddate->getTimestamp();

      $startdate=new UTCDateTime(strtotime($project['datentp']));
      $startdate=$startdate->toDateTime();
      $startdate=$startdate->getTimestamp();

      $startdateround = round(($startdate*1000-$today)/(2629743));
      $enddateround = round(($enddate*1000-$today)/(2629743));
      $averagePERmonth = $project['dollarvalueinhouse']/($enddateround-$startdateround);
      array_push($averagePERmonthARRAYperPROJECT,$project['dollarvalueinhouse']/($enddateround-$startdateround));

      if ($enddate>$maxenddate) {
        $maxenddate=$enddate;
      };
      $MAXxDATE=ceil(($maxenddate*1000-$today)/2629743);
    };

    //I have to run this again because the MAXxDATE is used for establishing
    foreach($projects as $project)
    {
      $enddate=$project['dateenergization']
      $enddate=$enddate->toDateTime();
      $enddate=$enddate->getTimestamp();

      $startdate=$project['datentp'];
      $startdate=$startdate->toDateTime();
      $startdate=$startdate->getTimestamp();

      $startdateround = round(($startdate*1000-$today)/(2629743));
      $enddateround = round(($enddate*1000-$today)/(2629743));
      $averagePERmonth = $project['dollarvalueinhouse']/($enddateround-$startdateround);
      array_push($averagePERmonthARRAYperPROJECT,$project['dollarvalueinhouse']/($enddateround-$startdateround));
      $totalPERmonthARRAY=[];
      $averagePERmonthARRAYperROW=[];
      //This is for populating zeros in the array before the data starts (depends on project start date)
      $x=0;
      for ($x;$x<($startdateround);$x++) 
      {
        $averagePERmonthARRAYperROW[$x]=0;
      };

      //There's where the hour/time distribution is placed, next step, need to change the distribution to an array that is imported and expandable to the time window to account for variable time distribution.  Specifically, the "averagePERmonth" variable will need to change to an array that fits the project type curve of when we spend our time. 
      $x=$startdateround + $FIELDS;
      setlocale(LC_MONETARY, 'en_US.UTF-8');
      for ($x;$x<($enddateround+$FIELDS);$x++) 
      {
        $averagePERmonthARRAYperROW[$x]=money_format('%.0n',round($averagePERmonth));
      };

      //This is for the total/footer row;
      $x=0;
      for ($x; $x<($MAXxDATE-$enddateround);$x++)
      {
      array_push($averagePERmonthARRAYperROW,0);
      };
      $project['averagePERmonth']=$averagePERmonthARRAYperROW;
      $total_footer_array = array_map(function () {return array_sum(func_get_args()); }, $total_footer_array, $project['averagePERmonth']);
      $lastarray = $project['averagePERmonth'];
    };      

    //This is the header for the months calculation.  Basically, the first month is to be the next month (not the current month).
    $x=5;
    $z=0;
    for ($x;$x<($MAXxDATE+$FIELDS);$x++)
    {
      $th_headerMonthBins[$x]="" . date("M-y",((int)$today+2629743*($z+1)));
      $z++;
    };
    $x=0;
    dd($th_headerMonthBins);
    return view('pages.wonprojectsummary', compact('projects', 'th_headerMonthBins', 'testarray', 'averagePERmonthARRAYperROW', 'total_footer_array'));
  }



  public function search(Request $request)
  {
    $term = $request['search'];
    if (isset($term)) { 
      $projects = Project::whereRaw(['$text' => ['$search' => $term]])->get();
      return view('pages.projectindex', compact('projects')); 
    }
    else {
      return redirect('/projectindex')->with('Please enter a search term to search.');
    }
  }

  public function edit_project($id)
  {
    $project = Project::find($id);
    return view('pages.editproject', compact('project'));
  }


  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

