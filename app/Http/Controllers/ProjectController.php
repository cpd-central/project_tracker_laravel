<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectController extends Controller
{
	public function new_project(){
		return view('project.newproject');
	}
	public function save(Request $request){
		//dd($request->all());
		$project = new Project($request->all());
		$project->save();
	
		if($project){
			return redirect()->route('home');
		}else{
			return back();
		}	
	}
}
