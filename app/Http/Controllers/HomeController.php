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
    {   //if the date 7 days from now isn't the same month as current, then billing is due in 7 days
        if(date('F', strtotime('+7 day')) != date('F')){ //so, let's build the billing_widget data
            $billing = $this->billing_widget();
        }
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

    public function update_role(Request $request)
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

    public function edit_account($id)
    {
        $user = User::find($id);
        return view('pages.editaccount', compact('user'));
    }

    public function update_account(Request $request, $id){
        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();
        return redirect()->route('pages.roles')->with('success', 'Success! User has been successfully updated.');
    }
}
