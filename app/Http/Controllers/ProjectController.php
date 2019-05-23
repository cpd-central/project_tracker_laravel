<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Project;

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

  public function new_project()
  {
    return view('pages.newproject');
  }

  public function create(Request $request)
  {
    $project = new Project();
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Project has been successfully added.');
  }

  public function update(Request $request, $id)
  {
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
    return view('pages.editproject', compact('editproject','project'));
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

