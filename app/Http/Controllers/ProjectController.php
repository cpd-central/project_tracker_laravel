<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Project;
use MongoDB\BSON\UTCDateTime;   //Imported for strToDate function
use UTCDateTime\DateTime;
use UTCDateTime\DateTime\DateTimeZone;

class ProjectController extends Controller
{
  protected function store($project, $req)
  {
    $project->cegproposalauthor= $req->get('cegproposalauthor');
    $project->projectname= $req->get('projectname');
    $project->clientcontactname= $req->get('clientcontactname');
    $project->clientcompany = $req->get('clientcompany');
    $project->mwsize = (int) $req->get('mwsize');
    $project->voltage = (int) $req->get('voltage');
    $project->dollarvalueinhouse = (int) $req->get('dollarvalueinhouse');
    $project->dateproposed = $this->strToDate($req->get('dateproposed'));
    $project->datentp = $this->strToDate($req->get('datentp'));
    $project->dateenergization = $this->strToDate($req->get('dateenergization'));
    $project->projecttype = $req->get('projecttype_checklist');
    $project->epctype = $req->get('epctype_checklist');
    $project->projectstatus = $req->get('projectstatus');
    $project->projectcode = $req->get('projectcode');
    $project->projectmanager = $req->get('projectmanager');
    dd($project);
    //$project->save();
  }

  protected function validate_request($req)
  {
    $this->validate($req, [
      'cegproposalauthor' => 'required',
      'projectname' => 'required',
      'clientcontactname' => 'required'
    ]);
  }

    protected function strToDate($req)
  {
    $timestamp = strtotime($req);                      //Must use MM/DD/YYY because MM-DD-YYYY is actually european so it will be used wrong
    $date = new \DateTime($req, new \DateTimeZone('America/Chicago'));
    $utcdatetime = new UTCDateTime(intval($date) * 1000);
    return $date;
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

  public function summary()
  {
    //Steve's code goes here
    return view('pages.wonprojectsummary');
  }

  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

