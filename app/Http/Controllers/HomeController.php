<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $billing = $this->billing_widget();
        return view('dashboard', compact('billing'));
    }

    protected function billing_widget()
    {
        $need_billing = array();
        $projects = Project::where('projectmanager', auth()->user()->name)->get();
        $year = date('Y');
        $month = date('F');
        foreach($projects as $project){
            if(isset($project['bill_amount'][$year])){
                if(!in_array($month ,array_keys($project['bill_amount'][$year]))){
                    array_push($need_billing, $project);
                }
            }
        }
        return $need_billing;
    }

    public function edit_roles()
    {
        $users = User::all();
        return view('pages.roles', compact('users'));
    }

    public function destroy($id)
    {
        if(isset($id)){
            $user = User::find($id);
            $user->delete();
        }
        return redirect('/home');
    }

    public function update(Request $request)
    {
        $users = User::all();
        foreach($users as $user){
            if($user['role'] == 'sudo'){
                continue;
            }
            $stringSplit = explode(".", $user['email']);
            $string = $stringSplit[0]."_".$stringSplit[1];
            $user['role'] = $request[$string];
            $user->save();
        }
        return redirect('/home');
    }
}
