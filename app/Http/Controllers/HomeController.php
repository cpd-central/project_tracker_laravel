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
     * @return view - returns the dashboard page.
     */
    public function index()
    {   //if the date 7 days from now isn't the same month as current, then billing is due in 7 days
        $billing = null; //Made null so it doesn't throw error.
        if(date('F', strtotime('+7 day')) != date('F')){ //so, let's build the billing_widget data
            $billing = $this->billing_widget();
        }
        return view('dashboard', compact('billing'));
    }

    /**
     * Builds the billing widget by checking if the user's name occurs in any project's project
     * manager section. If so, checks to see if the last month had billing information. If it's
     * not filled out, it pushes it to an array to be displayed on the dashboard.
     *
     * @return Array - array of projects that need billing for the last month.
     */
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

    /**
     * Pulls all users for the roles/'Account Directory' page.
     *
     * @return view - returns the roles/'Account Directory' page.
     */
    public function edit_roles()
    {
        $users = User::all();
        return view('pages.roles', compact('users'));
    }

    /**
     * Takes the $id of the user to be terminated.
     * @param $id - the id of the user to be terminated.
     * @return redirect - redirects the admin to the dashboard.
     */
    public function destroy($id)
    {
        if(isset($id)){
            $user = User::find($id);
            $user->delete();
        }
        return redirect('/home');
    }

    /**
     * Updates all user roles based on the radio button fields on the page.
     * @param Request $request
     * @return redirect - redirects the admin to the dashboard.
     */
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

    /**
     * Finds and compacts the user to be edited.
     * @param $id - the id of the user to be edited.
     * @return view - view of the edit account page for the selected user.
     */
    public function edit_account($id)
    {
        $user = User::find($id);
        return view('pages.editaccount', compact('user'));
    }

    /**
     * Checks if inputted number field was left blank. Assigns the number -1 and
     * parses it from String to Integer.
     * @param $integer - inputted number to be checked and converted. 
     * @return $integer
     */
    protected function intCheck($integer)
    {
        if($integer == null || $integer == ""){
            $integer = 0;
        }
        return ((int)$integer);
    }

    /**
     * Updates the user's name and email based on $id.
     * @param $id - the id of the user to be updated.
     * @param Request $request
     * @return redirect - redirects the admin to the roles/'Account Directory' page with a success message.
     */
    public function update_account(Request $request, $id){
        $user = User::find($id);
        $user->name = $request->get('name');
        $user->nickname = $request->get('nickname');
        $user->email = $request->get('email');
        $user->jobclass = $request->get('jobclass');
        $user->perhourdollar = $this->intCheck($request->get('perhourdollar'));
        $user->role = $request->get('role');
        $user->save();
        return redirect()->route('pages.roles')->with('success', 'Success! User has been successfully updated.');
    }
}
