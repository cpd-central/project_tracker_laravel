<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Project;
use MongoDB\BSON\UTCDateTime; 
use MongoDB\BSON\Decimal128; 
use App\Charts\HoursChart;
use UTCDateTime\DateTime;
use UTCDateTime\DateTime\DateTimeZone;


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
    $project->mwsize = $this->intCheck((int) $req->get('mwsize'));
    $project->voltage = $this->intCheck((int) $req->get('voltage'));
    $project->dollarvalueinhouse = $this->intCheck((int) $req->get('dollarvalueinhouse'));
    $project->dateproposed = $this->strToDate($req->get('dateproposed'));
    $project->datentp = $this->strToDate($req->get('datentp'));
    $project->dateenergization = $this->strToDate($req->get('dateenergization'));
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

  protected function strToDate($date_string)
  {
    if (isset($date_string))
    {
      $php_date = new \DateTime($date_string, new \DateTimeZone('America/Chicago'));
      $date = new UTCDateTime($php_date->getTimestamp() * 1000);
    }
    else {
      $date = "";
    }
    return $date;
  }

  protected function dateToStr($mongo_date)
  {
    if (is_string($mongo_date))
    {
      $date_string = $mongo_date;
    }
    else {
      $php_datetime = $mongo_date->toDateTime();
      //Note, this is the format needed to display in Chrome.  If Someone uses a different browser, 
      //we will need to think through further. 
      $date_string = $php_datetime->format('Y-m-d');
    }
    return $date_string;
  }

  protected function intCheck($integer)
  {
    if($integer == null || $integer == "")
    {
      $integer = -1;
    }
    return $integer;
  }

  protected function intDisplay($integer)
  {
    if($integer == -1)
    {
      $integer = "";
    }
    return $integer;
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
    foreach($projects as $project)
    {
      $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
      $project['datentp'] = $this->dateToStr($project['datentp']);
      $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    } 
    return view('pages.projectindex', compact('projects'));
  }

  public function indexwon()
  {
    #$project=Project::all()->where('projectstatus','Won')->first();
    #$vardate=$project['dateenergization'];
    #$vardate=new UTCDateTime(strtotime($vardate));
    #$vardate=$vardate->toDateTime();
    #$vardate=$vardate->getTimestamp();
    #$today=time();
    #$result=ceil(($vardate*1000-$today)/2629743);
    #echo "vardate:" . $vardate*1000 . "<br>";
    #echo "today:" . $today . "<br>";
    #echo "result:" . $result . "<br>";

    $maxenddate = 0;
    $numQUERYfieldsY = 0;
    $MAXxDATE = 0;
    $today=time();

    $projects=Project::all()->where('projectstatus','Won');

    foreach($projects as $project)
    {
      $enddate=new UTCDateTime(strtotime($project['dateenergization']));
      $enddate=$enddate->toDateTime();
      $enddate=$enddate->getTimestamp();
      if ($enddate>$maxenddate) {
        $maxenddate=$enddate;
      };
      $numQUERYfieldsY++;
    };      

    $MAXxDATE=ceil(($maxenddate*1000-$today)/2629743);
    #echo "MAXxDATE:" . $MAXxDATE . "<br>";
    return view('pages.wonprojectsummary', compact('projects','MAXxDATE','today'));
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
    $project['mwsize'] = $this->intDisplay($project['mwsize']);
    $project['voltage'] = $this->intDisplay($project['voltage']);
    $project['dollarvalueinhouse'] = $this->intDisplay($project['dollarvalueinhouse']);
    $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
    $project['datentp'] = $this->dateToStr($project['datentp']);
    $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    return view('pages.editproject', compact('project'));
  }


  public function hours_graph(Request $request)
  {
    $projects = DB::collection('hours_by_project')->get()->sortBy('code');
    #$projects = Project::whereRaw(['hours_data' => ['$exists' => 'true']])->get();
    
    function get_chart_info($id)
    {
      $selected_project = DB::collection('hours_by_project')->where('_id', $id)->first();

      if ($selected_project)
      {
        $hours_data = $selected_project['hours_data'];

        $years = array_keys($hours_data);
        $hours_arr = array();
        $labels_arr = array();

        foreach($years as $year)
        {
          $year_hours_data = $hours_data[$year];
          $months = array_keys($year_hours_data);
          foreach($months as $month)
          {
            array_push($labels_arr, $month . '-' . $year);

            $people_hours = $year_hours_data[$month];

            $total_project_hours = $people_hours['Total'];
            array_push($hours_arr, $total_project_hours);
          }
        }
      //We only want the data from the first non zero entry to the lst non zero entry in the set 
      //array_filter will remove all zero entries
      //we take the start key and end key of the zeros removed array
      //we use these keys to get the slice of the original array between those keys 
      $hours_array_filtered = array_filter($hours_arr);
      $start_key = key($hours_array_filtered);
      //moves pointer to end
      end($hours_array_filtered);
      $end_key = key($hours_array_filtered);

      $hours_arr_start_end = array_slice($hours_arr, $start_key, $end_key - $start_key + 1);
      $labels_arr_start_end = array_slice($labels_arr, $start_key, $end_key - $start_key + 1);

      $labels = $labels_arr_start_end;
      $dataset = array($selected_project['code'] . ' Hours', 'line', $hours_arr_start_end); 
      return array('labels' => $labels, 'dataset' => $dataset); 
      }
      else
      {
      return Null;
      } 
    }
    
    
    $chart_info = get_chart_info($request['project_id']);
    if (isset($chart_info))
    {
      $chart = new HoursChart;

      $chart->labels($chart_info['labels']);
      $chart->dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2])->options([
        'borderColor'=>'#3cba9f', 'fill' => False]);
      return view('pages.hoursgraph', compact('projects', 'chart'));
    }
    else 
    {
      return view('pages.hoursgraph', compact('projects'));
    } 
  }

  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

