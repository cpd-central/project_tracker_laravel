<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\Timesheet;

class ScriptController extends Controller
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

    /**
     * Shows the application dashboard.
     *
     * @return view - returns Scripts dashboard.
     */
    public function index()
    {   
        return view('pages.scripts');
    }
    /**
     * Shows the application dashboard.
     *
     * @return view - returns Scripts dashboard.
     */
    public function execute(Request $request)
    {   
        $all_projects = Project::whereNotNull('projectcode')->get();
        $all_users = User::all();
        $year = date("Y");
        foreach($all_projects as $project){
            $hours_data = [];
            $total = 0;
            $code = $project['projectcode'];
            foreach($all_users as $user){
                
            }
        }

        $success = "Script successfully executed.";
        return redirect('/scripts')->with('success', $success);
    }
}