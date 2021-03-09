<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\Timesheet;
use App\Page;
use App\DevRequest;
use File;

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
        $first_payperiod_end = strtotime('2019-08-25'); //The very first pay period end date recorded
        //first_payperiod_end modulus 14 days in epoch second divided by 1 day in epoch seconds.
        if(($first_payperiod_end % 1209600)/86400 >= 3){
            $this->timesheetStatusCheck();
        }
        return view('dashboard', compact('billing'));
    }

    /**
     * Retrieves all Timesheets that have 'pay_period_sent' as false.
     * It then checks to see if the timesheet has been updated in the last 3 days, and if so
     * we count that at the timesheet sent so we set it to true and save it.
     */
    protected function timesheetStatusCheck(){
        $timesheets = Timesheet::whereRaw(['$and' => array(['pay_period_sent' => ['$ne' => null]], ['pay_period_sent' => false])])->get();
        $today = new \DateTime("now", new \DateTimeZone("UTC"));
        foreach($timesheets as $timesheet){
            $diff = $today->diff($timesheet['updated_at']);
            if($diff->d < 3){
                $timesheet['pay_period_sent'] = true;
                $timesheet->save();
            }
        }
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
     * Pulls all users for the Account Directory page.
     *
     * @return view - returns the Account Directory page.
     */
    public function account_directory()
    {
        $users_active = User::where('active', true)->get()->sortByDesc('jobclass');
        $users_inactive = User::where('active', false)->get()->sortByDesc('jobclass');
        return view('pages.accountdirectory', compact('users_active', 'users_inactive'));
    }

    /**
     * Pulls all page visit logs for the logs page.
     *
     * @return view - returns the logs page.
     */
    public function logs(Request $request)
    {
        $term = $request['sort'];
        if(!isset($term) || $term == "-----Select-----"){   
            $pages = Page::Where('name', '!=', "history")->get();
            $logs = [];
            foreach($pages as $page){
                array_push($logs, $page);
            }
            return view('pages.logs', compact('logs'));
        }
        else{
            $pages = Page::Where('name', $term)->get();
            $logs = [];
            foreach($pages as $page){
                array_push($logs, $page);
            }
            return view('pages.logs', compact('logs', 'term'));
        }
    }
    

    /**
     * Takes the $id of the user to be activated/deactivated.
     * @param $id - the id of the user to be activated/deactivated.
     * @return redirect - redirects the admin to the dashboard.
     */
    public function activation($id)
    {
        if(isset($id)){
            $user = User::find($id);
            if(isset($user->active)){
                if($user->active == true){
                    $user->active = false;
                }
                else{
                    $user->active = true;
                }
            }
            else{
                $user->active = true;
            }
        }
        $user->save();
        return $this->account_directory();
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
     * @return redirect - redirects the admin to the Account Directory page with a success message.
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
        return redirect()->route('pages.accountdirectory')->with('success', 'Success! User has been successfully updated.');
    }

    public function dev_index()
    {
        $reqs = DevRequest::all()->sortByDesc('date');
        return view('pages.devindex', compact('reqs'));
    }

    public function dev_request()
    {
        return view('pages.devrequest');
    }

    public function dev_create(Request $request, $id = null)
    {
        //Make sure that Subject and Body were provided, and that image meets the requirements.
        $messages = array(
            'subject.required' => 'A Subject is required.',
            'body.required' => 'A Body description is required.'
            );
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'subject' => 'required',
            'body' => 'required',
        ], $messages);

        $dev = new DevRequest();
        $dev->proposer = $request['proposer'];
        $dev->subject = $request['subject'];
        $dev->body = $request['body'];
        $dev->date = $request['date'];
        $time = time();
        if(!empty($request->image)){
            $imageName = $time.'.'.$request->image->extension();
            $dev->image = $imageName;
            $request->image->move(public_path('img/dev'), $imageName);
        }
        $dev->save();

        return redirect('/devindex')->with('success','Request has been created.');
    }

  /**
   * Finds a project in the database by $id and deletes it from the database.
   * @param $id
   * @return redirect /projectindex
   */
  public function dev_delete($id)
  {
    $request = DevRequest::find($id);
    $imageSet = false;
    if(!empty($request['image'])){
        $imageSet = true;
        $path = public_path("img/dev/{$request['image']}");
    }
    $request->delete();
    if($imageSet) {
        unlink($path);
    }
    return redirect('/devindex')->with('success','Request has been deleted.');
  }

}
