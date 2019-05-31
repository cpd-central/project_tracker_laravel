<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Project;
use MongoDB\BSON\UTCDateTime; 
use MongoDB\BSON\Decimal128; 

class ProjectController extends Controller
{
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
    return redirect('/projectindex')->with('success', 'Project has been successfully added.');
  }

  public function update(Request $request, $id)
  {
    $this->validate_request($request);   
    $project = Project::find($id);
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Project has been successfully updated');
  }

  public function index()
  {
    $projects=Project::all();
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
    $projects = Project::whereRaw(['$text' => ['$search' => $term]])->get();
    return view('pages.projectindex', compact('projects')); 
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

