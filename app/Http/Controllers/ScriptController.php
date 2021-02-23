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
        $d = $request;
        return view('dashboard');
    }
}