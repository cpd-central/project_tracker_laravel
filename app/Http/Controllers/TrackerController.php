<?php

namespace App\Http\Controllers;

//Sam code
use App\ProjectTracker;
use Illuminate\Http\Request;

use MongoDB\BSON\UTCDateTime;
use UTCDateTime\DateTime;
use UTCDateTime\DateTime\DateTimeZone;

class TrackerController extends Controller
{
//Sam Code
  public function index(){
    $data = ProjectTracker::all();
    foreach($data as $d)
    {
      $d = $this->displayFormat($d);
    }
    
    return view('pages.projecttracker', compact('data'));
  }


  protected function displayFormat($project){
    $project['due'] = $this->dateToStr($project['due']);
    return $project;
    }

  public static function dateToStr($mongo_date)
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

  protected function strToDate($date_string, $unknown)
  {
    if (isset($date_string))
    {
      #this is just in case a "none" or "unknown" energization slips in as a won project 
      if ($date_string == "None" or $date_string == "Unknown")
      {
        return $date_string;
      } 
      else 
      {
        $php_date = new \DateTime($date_string, new \DateTimeZone('America/Chicago'));
        //note this is a mongodb UTCDateTime 
        $date = new UTCDateTime($php_date->getTimestamp() * 1000);
      } 
    }
    else {
      if(isset($unknown)){
        $date = "Unknown";
      }
      else{
        $date = "None";
      }
    }
    return $date;
  }

  public function add_project(){
    return view('pages.newtrackerproject');
  }

  public function create(Request $request)
  {
    $this->validate_request($request);
    $project = new ProjectTracker();
    $project->created_by = auth()->user()->email;
    $this->store($project, $request);
    return redirect('/projecttracker')->with('Success!', 'Project has been successfully added.');
  }

  protected function validate_request($req)
  {
    $messages = array(
      'project-name.required' => 'The Project Name is required.',
      'project-due.required' => 'The Project Due Date is required.',
    );
    $this->validate($req, [
      'project-name' => 'required',
      'project-due' => 'required'
    ], $messages);
  }

  protected function store($project, $req)
  {
    $project->name= $req->get('project-name');
    $project->due= $this->strToDate($req->get('project-due'), null);
    $project->save();
  }

  public function edit_project($id)
  {
    $project = ProjectTracker::find($id);
    $project = $this->displayFormat($project);

    return view('pages.edittracker', compact('project'));
  }

  public function update(Request $request, $id){
    $this->validate_request($request);   
    $project = ProjectTracker::find($id);  
    $this->store($project, $request);
    return redirect('/projecttracker')->with('Success!', 'Project has been successfully updated');
  }

  public function destroy($id)
  {
    $project = ProjectTracker::find($id);
    $project->delete();
    return redirect('/projecttracker')->with('success','Project has been deleted');
  }
}