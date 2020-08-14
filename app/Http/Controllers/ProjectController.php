<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Timesheet;
use App\User;
use MongoDB\BSON\UTCDateTime; 
use App\Charts\HoursChart;

use DateInterval;
use DatePeriod;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Date;

class ProjectController extends Controller
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
   * Stores attributes from the $req to $project and is saved to the database.
   * @param $project - variable type Project to be saved to the database.
   * @param $req - Request variable with attributes to be assigned to $project.
   */
  protected function store($project, $req)
  {
    $project->cegproposalauthor= $req->get('cegproposalauthor');
    $project->projectname= $req->get('projectname');
    $project->clientcontactname= $req->get('clientcontactname');
    $project->clientcompany = $req->get('clientcompany');
    $project->state = $req->get('state');
    $project->utility = $req->get('utility');
    $project->mwsize = $this->floatCheck($req->get('mwsize'));
    $project->voltage = $this->floatCheck($req->get('voltage'));
    $project->dollarvalueinhouse = $this->intCheck($req->get('dollarvalueinhouse'));
    $project->dateproposed = $this->strToDate($req->get('dateproposed'), null);
    $project->datentp = $this->strToDate($req->get('datentp'), null);
    $project->dateenergization = $this->strToDate($req->get('dateenergization'), $req->get('dateenergizationunknown'));
    $project->monthlypercent = $this->floatConversion($req->get('monthly_percent'));
    $project->projecttype = $req->get('projecttype_checklist');
    $project->epctype = $req->get('epctype_checklist');
    $project->projectstatus = $req->get('projectstatus');
    $project->projectcode = $req->get('projectcode');
    $project->projectmanager = $this->managerCheck($req->get('projectmanager'));
    $project->projectnotes = $req->get('projectnotes');
    $project->billingcontact = $req->get('billingcontact');
    $project->billingcontactemail = $req->get('billingcontactemail');
    $project->billingmethod = $req->get('billingmethod');
    $project->billingnotes = $req->get('billingnotes');
    $project->filelocationofproposal = $req->get('filelocationofproposal');
    $project->filelocationofproject = $req->get('filelocationofproject');
    $project->save();
  }

  /**
   * Requires certain fields to be filled in and validates. If statement for Won and Probable 
   * which results in more requirements. If required fields not provided, route is redirected back to page.
   * @param $req - Request variable with attributes to be assigned to $project.
   * @param $month - an option parameter. 
   */
  protected function validate_request($req, $id = 0)
  {
    $messages = array(
      'cegproposalauthor.required' => 'The CEG Proposal Author is required.',
      'projectname.required' => 'The Project Name is required.',
      'projectname.unique' => 'The Project Name must be unique.',
      'clientcontactname.required' => 'The Client Contact Name is required.',
      'dollarvalueinhouse.required' => 'The Dollar Value in-house expense is required.',
      'datentp.required' => 'The Date of Notice To Proceed is required',
      'dateenergization.required_unless' => 'The Date of Energization is required unless Date of Energization Unknown is checked.'
    );
    $this->validate($req, [
      'cegproposalauthor' => 'required',
      'projectname' => 'required|unique:projects,projectname,'.$id.',_id',
      'clientcontactname' => 'required'
    ], $messages);

    if($req['projectstatus'] == 'Won' || $req['projectstatus'] == 'Probable'){ 
      $this->validate($req, [
        'dollarvalueinhouse' => 'required',
        'datentp' => 'required',
        'dateenergization' => 'required_unless:dateenergizationunknown,on'
      ], $messages);
    }
  }

  /**
   * Calls other functions to reformat the data for display..
   * @param $project - project whose attributes are being reformatted for display.
   * @return $project
   */
  protected function displayFormat($project)
  {
    $project['mwsize'] = $this->numDisplay($project['mwsize']);
    $project['voltage'] = $this->numDisplay($project['voltage']);
    $project['dollarvalueinhouse'] = $this->numDisplay($project['dollarvalueinhouse']);
    $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
    $project['datentp'] = $this->dateToStr($project['datentp']);
    $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    return $project;
  }

  /**
   * Converts the date inputted string to a variable type Date. Stores it in the database.
   * If the received date is null, the date is set to the string "None".
   * @param $date_string - string to be converted to a variable type Date.
   * @return $date
   */
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

  /**
   * Converts the Date variable $mongo_date to a string in order to be displayed properly.
   * Reformats the String to 'Year-month-day'.
   * @param $mongo_date - date received from mongoDB. 
   * @return $date_string
   */
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

  /**
   * Checks if inputted number field was left blank. Assigns the number -1 and
   * parses it from String to Integer.
   * @param $integer - inputted number to be checked and converted. 
   * @return $integer
   */
  protected function intCheck($integer)
  {
    if($integer == null || $integer == ""){
      $integer = -1;
    }
    return ((int)$integer);
  }

  /**
   * Checks if inputted number field was left blank. Assigns the number -1 and
   * parses it from String to Float.
   * @param $float - inputted number to be checked and converted. 
   * @return $float
   */
  protected function floatCheck($float)
  {
    if($float == null || $float == ""){
      $float = -1;
    }
    return ((float)$float);
  }

  /**
   * Checks if multiple managers were inputted seperated by commas, then stores
   * them in a list.
   * @param $managers - inputted number to be checked and converted. 
   * @return $managerList
   */
  protected function managerCheck($managers)
  {
    if(isset($managers)){
      $managerList = explode(',', $managers);
      array_walk($managerList, function(&$x){$x = trim($x);});
      return $managerList;
    }
    else{
      return null;
    }
  }

  /**
   * If the number from the database was -1, it was originally null. Changes the value to null
   * and return its.
   * @param $integer - integer received from mongoDB. 
   * @return $integer
   */
  protected function numDisplay($num)
  {
    if($num == -1)
    {
      $num = "";
    }
    return $num;
  }

  /**
   * Converts inputted monthly percents into a float. If percent input is
   * null, assigns it 0. array_walk converts all array values to floats.
   * @param $percents - array of inputted monthly percent fields. 
   * @return $percents
   */
  protected function floatConversion($percents){
    if($percents){
      foreach($percents as $percent){
        if($percent == null || $percent ==""){
          $percent = 0;
        }
      } 
      array_walk($percents, function(&$x){$x = (float)($x);});
    }
    return $percents;
  }

  /**
   * Returns a view of the newproject blade page.
   * @return view pages.newproject
   */
  public function new_project()
  {
      return view('pages.newproject');
  }

  public function copy_project($id)
  {
    $project = Project::find($id);
    $project = $this->displayFormat($project);
    return view('pages.editproject', compact('project'));
  }

  /**
   * Creates a new project to be stored in the database. Validates the 
   * $request and assigns the user that created it to the project along with the $request
   * attributes. Redirects the route to the project index page.
   * @param $req - Request variable with attributes to be assigned to $project. 
   * @return redirect /projectindex
   */
  public function create(Request $request, $id = null)
  {
    $this->validate_request($request);
    $project = new Project();
    $project->created_by = auth()->user()->email;
    $this->store($project, $request);
    if($id != null){
      return redirect('/projectindex')->with('success', 'Success! Project has been copied and successfully added.');
    }else{
      return redirect('/projectindex')->with('success', 'Success! Project has been successfully added.');
    }
  }

  /**
   * Updates a project in the database that was edited with the new changes.
   * @param $request - Request variable with attributes to be assigned to $project.
   * @param $id - the unique id of the project to be updated.
   * @return redirect /projectindex
   */
  public function update(Request $request, $id)
  {
    $this->validate_request($request, $id);  
    $project = Project::find($id);   
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Success! Project has been successfully updated.');
  }

  /**
   * Billing app method
   * Finds all projects who had the bill/hold text field filled and saves them to the datebase.
   * @param Request $request
   */
  public function submit_billing(Request $request)
  {
      //$timestamp = $this->strToDate(date("Y-m-d H:i:s", time()), null);
      //dd($timestamp);
      $id_billing_array=array();
      $x=0;
      for ($x=0; $x <= $request['graph_count']; $x++) 
      {
        if ($request['text_'.$x] != Null) { //If there's a billing field that has data, add it to the id array
          $id = $request['id_'.$x];
          $id_billing_array[$id] = $request['text_'.$x];
        } 
      }  

      if(!empty($id_billing_array)){  //If the id array is empty, then nothing needs to be saved.
      $project = Project::find($id); 
      $previous_month = date('F', strtotime('-21 day'));
      $year_of_previous_month = date('Y', strtotime('-21 day')); 

      foreach ($id_billing_array as $id_billing => $value) {
        $project = Project::find($id_billing);
        $bill_amount=$project->bill_amount;
        $bill_amount[$year_of_previous_month][$previous_month] = $value;
        $project->bill_amount = $bill_amount;
        $project->save();
      }
    }
    //$projects = Project::whereRaw(['$and' => array(['bill_amount' => ['$ne' => null]], ['bill_amount' => ['$exists' => 'true']])])->get()->sortBy('projectname');
    return redirect('/monthendbilling')->with('Success!', 'Billing has been successfully updated');;
  }

  /**
   * Method sets sort term and finds projects that have a bill_amount array.
   * @param Request $request
   * @return view 'monthendbilling' with 'projects' and 'term'
   */
  public function billing(Request $request){
    $term = $request['sort'];
    if(!isset($term)){
      $term = "projectmanager";
    }
    $previous_month = date('F', strtotime('-21 day'));
    $year_of_previous_month = date('Y', strtotime('-21 day'));
    $projects = Project::whereRaw(['$and' => array(['bill_amount' => ['$ne' => null]], ['bill_amount' => ['$exists' => 'true']])])->get()->sortBy($term);
    return view('pages.monthendbilling', compact('projects', 'term', 'previous_month', 'year_of_previous_month'));
  }

  /**
   * If the current user has a role that is not a user, all projects are retrieved to be viewed. 
   * Otherwise, the projects retrieved are the ones only the user role type user is associated with, such
   * as their name matching the cegproposalauthor, their name matching the projectmanager field, or
   * their email matching the created_by field stored in the project.
   * @return view projectindex
   */
  public function index(Request $request)
  {
    $search = $request['search'];
    $term = $request['sort'];
    $invert = $request['invert']; 
    if(isset($search) || (isset($term) && $term != "-----Select-----")){
      $projects = $this->search($search, $term, $invert);
    }
    else{
      $projects=Project::all();
    }
    $codes = [];
    foreach($projects as $project)
    {
      $project = $this->displayFormat($project);
      if($project['projectcode'] != null){
        $codes[$project['projectcode']] = $project['projectname'];
      }
    }
    $ref_list = Timesheet::where('name', 'reference_list')->get();
    $ref_list = $ref_list[0]['codes'];
    $missing_projects = [];
    foreach(array_keys($ref_list) as $ref){
      if(!array_key_exists($ref, $codes)){
        $missing_projects[$ref] = $ref_list[$ref];
      }
    }
    return view('pages.projectindex', compact('projects', 'term', 'search', 'invert', 'missing_projects'));
  }

  /**
   * Converts the mongo UTC datetime that comes out of the database to a php datetime and returns 
   * the start and end datetimes.
   * @param $proj - used to get the datentp and dateenergization.
   * @return array containing $start & $end
   */
  protected function get_project_start_end($proj)
  {
    //convert the mongo UTC datetime that comes out of the database to a php datetime 
    $start = $proj['datentp']->toDateTime();
    $end = $proj['dateenergization']->toDateTime();
    return ['start' => $start, 'end' => $end];
  }

  /**
   * Formats an array with a monthly interval from start to end date in order to display
   * the monthly budget for a project on indexwon().
   * @param $start
   * @param $end
   * @param $int - used as a monthly interval
   * @param $format
   * @return array $arr
   */
  protected function get_date_interval_array($start, $end, $int, $format)
  {
    $interval = DateInterval::createFromDateString($int);
    $period = new DatePeriod($start, $interval, $end);

    $arr = array();
    foreach($period as $dt)
    {
      array_push($arr, $dt->format($format));
    }
    return $arr;
  }

  /**
   * Array to display the monthly budget.
   * @param $project
   * @param $dollars_arr
   * @param $months
   * @return $dollars_arr
   */
  protected function add_dollars($project, $dollars_arr, $months)
  {
    $dollars_arr = $dollars_arr + $project['per_month_dollars'];
    foreach ($months as $month)
    {
      $dollars_arr[$month] = round($dollars_arr[$month] + $project['per_month_dollars'][$month], 0);
    }
    return $dollars_arr; 
  }

  /**
   * Queries for project status type 'Won' & 'Probable', just 'Won', or only 'Probable'. If user role is type 
   * user, then only projects they are associated with will show. Creates Bar graph at top and
   * organizes page by a row for each project split into monthly budgets for future dates.
   * If there are no projects in the query, returns and displays message stating no projects were found.
   * @return view pages.wonprojectsummary
   */
  public function indexwon(Request $request)
  {
    if($request['projectstatus'] == 'Won'){
      $projects=Project::all()->where('projectstatus','Won');
      $projectStatus = "Won";
    }
    else if($request['projectstatus'] == 'Probable'){
      $projects=Project::all()->where('projectstatus','Probable');
      $projectStatus = "Probable";
    }
    else{
      $projects=Project::where('projectstatus','Won')->orWhere('projectstatus','Probable')->get();
      $projectStatus = "All";
    }
    
    if (count($projects) > 0)
    {
      if (!isset($request['switch_chart_button'])) 
      {
        $chart_type = 'won_prob';
      }
      else
      { 
        $chart_type = $request['switch_chart_button'];
      } 
      //1. Get Max end date in order to establish the # of columns needed for the table
      //Also, get smallest start date to establish the beginning of the array 
      $start_dates = array();
      $end_dates = array();
      foreach($projects as $key => $project)
      { 
        if (isset($project['per_month_dollars']))
        {
          $old_per_month_dollars = $project['per_month_dollars'];
        } 
        if($project['dateenergization'] == "Unknown"){
          unset($projects[$key]);
          continue;
        }
        $start_end = $this->get_project_start_end($project);
        $start_date = $start_end['start'];
        $end_date = $start_end['end']; 
        //put these in the arrays of all start and end dates
        array_push($start_dates, $start_date);
        array_push($end_dates, $end_date);
        //get the total dollars and divide by number of months.  create an array of that for this specific project 
        $project_dollars = $project['dollarvalueinhouse'];
        //need to use the specific start and end for this project 
        $project_months = $this->get_date_interval_array($start_date, $end_date, '1 month', 'M-y');
        $num_months = count($project_months);
        if($num_months <= 0){
          $project_per_month_dollars = array();
          foreach($project_months as $month)
          {
            $project_per_month_dollars[$month] = 0;
          }
          $project['per_month_dollars'] = $project_per_month_dollars;
        }
        else
        {
          if (isset($project['monthlypercent']))
          {
            //check if all values in the monthly percent array are 0 or if there are non zero elements
            $temp = array_filter($project['monthlypercent']);
            if (count($temp) > 0)
            {
              $project_per_month_dollars = array();
              $i = 0;
              foreach($project_months as $month)
              {
                //the only real difference between this and the else, is that we do the per month calc for
                //each iteration of the foreach loop, rather than all at once.
                //This could be cleaned up if we had 'monthlypercent' be an associative array with the 
                //month as the key
                $per_month_dollars = $project_dollars * $project['monthlypercent'][$i];
                $project_per_month_dollars[$month] = $per_month_dollars;
                $i++;
              }
            }
            //if not, do a flat distribution
            else
            {
              $per_month_dollars = $project_dollars / $num_months;
              $project_per_month_dollars = array();
              foreach($project_months as $month)
              {
                $project_per_month_dollars[$month] = $per_month_dollars;
              }
            }
          }
          else
          {
            $per_month_dollars = $project_dollars / $num_months;
            $project_per_month_dollars = array();
            foreach($project_months as $month)
            {
              $project_per_month_dollars[$month] = $per_month_dollars;
            }
          }
          $project['per_month_dollars'] = $project_per_month_dollars;
        }
      }
      if(empty($start_dates)){
        return view('pages.wonprojectsummary', compact('projects'));
      }
      //get the max end date and min start date
      $earliest_start = min($start_dates);
      $latest_end = max($end_dates);
      //create an array of months between these two dates 
      $months = $this->get_date_interval_array($earliest_start, $latest_end, '1 month', 'M-y'); 

      $today = date('M-y'); 
      //find today in the array of months and remove everything before it 
      $search_index = array_search($today, $months); 
      for ($i=0; $i<$search_index; $i++)
      {
        unset($months[$i]);
      }
      //create chart and add the months as labels 
      $chart = new HoursChart; 
      #$month_values = array_slice(array_values($months), 0, 12);
      $month_values = array_values($months);
      $chart->labels($month_values);
      $chart_colors = [
        'rgb(255, 99, 132, 0.4)',
        'rgb(75, 192, 192, 0.4)',
        'rgb(255, 159, 64, 0.4)',
        'rgb(54, 162, 235, 0.4)',
        'rgb(255, 205, 86, 0.4)',
        'rgb(153, 102, 255, 0.4)'];
      $max_color_counter = count($chart_colors) - 1;
      $color_counter = 0; 
      //now loop through the projects again and update the array to have the months we are displaying, and fill with zeros for the rest
      $total_dollars_won = array();
      $total_dollars_probable = array();
      $total_dollars_total = array();

      foreach($projects as $project)
      {
        //find first key of month array
        $first_month = array_key_first($months);
        $new_project_per_month_dollars = array(); 
        foreach($months as $month)
        {
          //check if each month is in the current project's array.  If it is, then simply put the existing value in the new array
          //if it isn't, put zero in the new array 
          if (array_key_exists($month, $project['per_month_dollars']))
          { 
            //round to 0 decimals 
            $new_project_per_month_dollars[$month] = round($project['per_month_dollars'][$month], 0);
          }
          else 
          {
            $new_project_per_month_dollars[$month] = 0;
          } 
        }
        //now re-write the project data with the new array 
        $project['per_month_dollars'] = $new_project_per_month_dollars;

        if ($project['projectstatus'] == 'Won')
        {
          $total_dollars_won = $this->add_dollars($project, $total_dollars_won, $months);
        }
        else 
        {
          $total_dollars_probable = $this->add_dollars($project, $total_dollars_probable, $months);
        }
        if ($chart_type == 'projects')
        {
          //add the project hours to the chart as a dataset 
          $dollar_values = array_values($project['per_month_dollars']);
          $chart->dataset("{$project['projectname']}", 'bar', $dollar_values)->options(['backgroundColor' => $chart_colors[$color_counter]]);
          $color_counter++;
          if ($color_counter > $max_color_counter)
          {
            $color_counter = 0;
          }
        }
        //formats the project data in order to display properly
        $project = $this->displayFormat($project);
        
        //need to clone the project to update the database without affecting the project to be displayed
        $project_to_save = clone $project;
        //Need the dates to be converted back to mongo dates
        #check if we have an old per_month_dollars.  if we do, we want to combine the new one with the old one before saving, in order to not override old months
        if (isset($old_per_month_dollars))
        {
          $project_to_save['per_month_dollars'] = array_merge($old_per_month_dollars, $project['per_month_dollars']);
        }
        $project_to_save->dateproposed = $this->strToDate($project['dateproposed'], null);
        $project_to_save->datentp = $this->strToDate($project['datentp'], null);
        $project_to_save->dateenergization = $this->strToDate($project['dateenergization'], null);
        $project_to_save->save();
      }

      $total_dollars = $total_dollars_won + $total_dollars_probable; 
      if ($chart_type == 'won_prob')
      {
        $dollar_values_won = array_values($total_dollars_won); 
        $dollar_values_probable = array_values($total_dollars_probable);
        $chart->dataset("Won Project Dollars Per Month", 'bar', $dollar_values_won)->options(['backgroundColor' => $chart_colors[1]]);
        $chart->dataset("Probable Project Dollars Per Month", 'bar', $dollar_values_probable)->options(['backgroundColor' => $chart_colors[0]]);
      }

      $options = [];
      $options['scales']['xAxes'][]['stacked'] = true;
      $options['scales']['yAxes'][]['stacked'] = true;
      $options['legend']['labels']['boxWidth'] = 10;
      $options['legend']['labels']['padding'] = 6;
      $chart->options($options);
      $chart->height(600);

      return view('pages.wonprojectsummary', compact('months', 'projects', 'total_dollars', 'chart', 'projectStatus', 'chart_type')); 
    }
    else 
    {
      return view('pages.wonprojectsummary', compact('projects'));
    }
  }


/**
 * Queries database by text field search. For users, it will only show the results on the projects
 * the user is associated with. For other roles, shows all project results.
 * @param $request - Request variable with attributes to be assigned to $project.
 * @return view projectindex
 */
  public function search($search_term, $sort_term, $invert)
  {
    $not_expired_projects = Project::where(array(['projectstatus', '<>', 'Expired']));
    if(isset($invert))
    {
      $asc_desc = 'desc';
    }
    else
    {
      $asc_desc = 'asc';
    }
    if (isset($search_term)) {
      if (isset($sort_term) && $sort_term != "-----Select-----"){
        $projects = Project::where('cegproposalauthor', 'regexp', "/$search_term/i")
                    ->orWhere('projectname', 'regexp', "/$search_term/i")
                    ->orWhere('clientcontactname', 'regexp', "/$search_term/i")
                    ->orWhere('clientcompany', 'regexp', "/$search_term/i")
                    ->orWhere('projectstatus', 'regexp', "/$search_term/i")
                    ->orWhere('projectcode', 'regexp', "/$search_term/i")
                    ->orWhere('projectmanager', 'regexp', "/$search_term/i")
                    ->orWhere('state', 'regexp', "/$search_term/i")
                    ->orWhere('utility', 'regexp', "/$search_term/i")
                    ->orWhere('projecttype', 'regexp', "/$search_term/i")
                    ->orWhere('epctype', 'regexp', "/$search_term/i")
                    ->orderBy($sort_term, $asc_desc)
                    ->get();             
      }
      else {
        $projects = Project::where('cegproposalauthor', 'regexp', "/$search_term/i")
                    ->orWhere('projectname', 'regexp', "/$search_term/i")
                    ->orWhere('clientcontactname', 'regexp', "/$search_term/i")
                    ->orWhere('clientcompany', 'regexp', "/$search_term/i")
                    ->orWhere('projectstatus', 'regexp', "/$search_term/i")
                    ->orWhere('projectcode', 'regexp', "/$search_term/i")
                    ->orWhere('projectmanager', 'regexp', "/$search_term/i")
                    ->orWhere('state', 'regexp', "/$search_term/i")
                    ->orWhere('utility', 'regexp', "/$search_term/i")
                    ->orWhere('projecttype', 'regexp', "/$search_term/i")
                    ->orWhere('epctype', 'regexp', "/$search_term/i")
                    ->get();
    }
    }

    else {
      $projects = $not_expired_projects->orderBy($sort_term, $asc_desc)->get();
    }

    foreach ($projects as $project) {
      $project = $this->displayFormat($project);
    }
    return $projects;

  }
 
  /**
   * Queries the database with the passed parameter $id to find the project
   * with the same id and displays it so the information can be edited.
   * @param $id
   * @return view editproject
   */
  public function edit_project($id)
  {
    $project = Project::find($id);
    $project = $this->displayFormat($project);

    return view('pages.editproject', compact('project'));
  }

  public function get_employee_list($keyword){
    //This array is for CEG personnel, the second field has no role in the code currently
    $employee_list = array();
    $users = User::all();
    foreach($users as $user){
      $user_array = array($user->nickname, $user->perhourdollar, $user->jobclass, $user->email);
      array_push($employee_list, $user_array);
    }
    return $employee_list;
    /*$employee_list = array( array("Vince"       ,"senior project manager"  ,170,"senior"         , "vince@ceg.mn"                  ),
                           array("Max"         ,"senior engineer"         ,160,"senior"         , "mbartholomay@ceg-engineers.com"),
                           array("Pete"        ,"senior project manager"  ,160,"senior"         , "pmalamen@ceg-engineers.com"    ),
                           array("Jim"         ,"senior project manager"  ,160,"senior"         , ""                              ),
                           array("Darko"       ,"project engineer - a"    ,120,"senior"         , "dborkovic@ceg-engineers.com"   ),
                           array("Rob"         ,"senior project engineer" ,130,"senior"         , "rduncan@ceg-engineers.com"     ),
                           array("Steve P."    ,"senior project engineer" ,130,"senior"         , "speichel@ceg-engineers.com"    ),
                           array("Shafqat"     ,"project engineer b"      ,110,"project"        , "siqbal@ceg-engineers.com"      ),
                           array("Nick"        ,"SCADA Engineer"          ,125,"SCADA"          , "ntmoe@ceg.mn"                  ),
                           array("Erin"        ,"project engineer - a"    ,120,"project"        , "ebryden@ceg-engineers.com"     ),
                           array("Yang"        ,"project engineer - a"    ,120,"project"        , "yzhang@ceg-engineers.com"      ),
                           array("Stephen K."  ,"project engineer - b"    ,110,"project"        , "skatz@ceg-engineers.com"       ),
                           array("Naga"        ,"project engineer - c"    ,95 ,"project"        , "nguddeti@ceg-engineers.com"    ),
                           array("Corey"       ,"project engineer - c"    ,95 ,"SCADA"          , "cdolan@ceg.mn"                 ),
                           array("Abdi"        ,"project engineer - b"    ,110,"project"        , "ajama@ceg-engineers.com"       ),
                           array("Sumitra"     ,"intern"                  ,60 ,"interns-admin"  , "schowdhary@ceg-engineers.com"  ),
                           array("Jacob R."    ,"project engineer - c"    ,95 ,"project"        , "jromero@ceg.mn"                ),
                           array("Brian"       ,"SCADA Engineers"         ,125,"SCADA"          , "bahlsten@ceg-engineers.com"    ),
                           array("Tom U."      ,"Project Managers"        ,125,"project"        , "turban@ceg-engineers.com"      ),
                           array("Jake C."     ,"Project Managers"        ,95 ,"project"        , "jcarlson@ceg-engineers.com"    ),
                           array("Jake M."     ,"project engineer - c"    ,95 ,"SCADA"          , "jmarsnik@ceg-engineers.com"    ),
                           array("Donna"       ,"admin"                   ,85 ,"interns-admin"  , "dsindelar@ceg-engineers.com"   ),
                           array("Letysha"     ,"cad technician drafter"  ,85 ,"drafting"       , "lbergstad@ceg-engineers.com"   ),
                           array("Kathy"       ,"electrical designer"     ,105,"drafting"       , "kburk@ceg-engineers.com"       ),
                           array("Tom M."      ,"cad technician drafter"  ,85 ,"drafting"       , ""                              ),
                           array("Julie"       ,"cad technician drafter"  ,85 ,"drafting"       , "jcasanova@ceg-engineers.com"   ),
                           array("Marilee"     ,"cad technician drafter"  ,85 ,"drafting"       , "mkaas@ceg-engineers.com"       ),
                           array("Joe M."      ,"cad technician drafter"  ,85 ,"drafting"       , "jmitchell@ceg-engineers.com"   ),
                           array("Bob B."      ,"cad technician drafter"  ,85 ,"drafting"       , "rbuckingham@ceg-engineers.com" ),
                           array("Mike"        ,"cad technician drafter"  ,85 ,"drafting"       , "mtuma@ceg-engineers.com"       ),
                           array("Bob S."      ,"cad technician drafter"  ,85 ,"drafting"       , ""                              ),
                           array("Karen"       ,"cad technician drafter"  ,85 ,"drafting"       , ""                              ),
                           array("Randy"       ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Josh"        ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Sam"         ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Nolan"       ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Graham"      ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Trevor"      ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Bjorn"       ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Keerti"      ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("Tim"         ,"intern"                  ,60 ,"interns-admin"  , ""                              ),
                           array("noname"      ,""                        ,60 ,"interns-admin"  , ""                              ));

    return $employee_list;*/
  }

  public function drafter_hours(Request $request)
  {
    $employeeLIST = $this->get_employee_list(null);
    
    $filter_all = false;
    if(null !== $request->get('toggle')){
      if($request->get('toggle') == 'true'){
        $filter_all = true;
      }
    }

    //truncate the employee list if we're in the drafter hours page
    //store the emails for the drafters 
    $employee_emails = array(); 
    //just a counter
    $z = 0; 
    foreach ($employeeLIST as $emp) {
      if ($emp[2] == 'drafting') {
        if ($emp[3]) 
          $employee_emails[$emp[0]] = $emp[3];
      }
      $z++;
    }
      
    $today = app('App\Http\Controllers\TimesheetController')->getDate();
    $end_date = clone $today;
    //date range of 14 days 
    $start_date = $today->sub(new DateInterval('P14D'));
    //now, we want access to some of the functions in our Timesheetcontroller, since the collecting of the 
    //drafters' hours is very similar to what we do in the timesheet app
    $date_arr = app('App\Http\Controllers\TimesheetController')->get_dates($start_date, $end_date)[0];
      
    $current_month = date('F');
    $previous_month = date('F', strtotime('-14 days'));
    $current_year = date('Y');
    $previous_year = date('Y', strtotime('-14 days')); //Not meant to be previous year other than January
      
    $non_zero_projects = Project::whereRaw([
      '$and' => array([
        'hours_data' => ['$exists' => 'true'],
        '$and' => array([
          "hours_data.{$previous_year}.{$previous_month}.Total"=> ['$exists' => true],  
          "hours_data.{$previous_year}.{$previous_month}.Total" =>['$ne'=>0]
        ])
      ])
    ])->get()->sortByDesc("hours_data.{$previous_year}.{$previous_month}.Total"); 
    //we really just need the codes from this, since that's what we will use to look up the hours in the timesheet
    $codes_arr = array();
    foreach($non_zero_projects as $project) {
      if(!in_array($project['projectcode'], $codes_arr)){
        array_push($codes_arr, $project['projectcode']);
      }
    }

    $choosen_line_colors = array('#396AB1','#DA7C30','#3E9651','#CC2529','#6B4C9A','#BFBF1E', '#00CCCC', '#6e4d00');
    $fill_colors = [
      'rgb(97, 136, 193, 0.4)',
      'rgb(255, 150, 89, 0.4)',
      'rgb(101, 171, 116, 0.4)',
      'rgb(214, 81, 84, 0.4)',
      'rgb(137, 112, 174, 0.4)',
      'rgb(245, 245, 116, 0.4)',
      'rgb(51, 214, 214, 0.4)',
      'rgb(159, 109, 0, 0.4)'];
    $c_color_loop = 0;
    $color_max = 7;

    //holds the data for all charts 
    $charts = array(); 
    $all_data_arr = array();
    foreach(array_keys($employee_emails) as $name) {
      //make the chart and add the labels
      $chart = new HoursChart;
      $chart->title($name);
      $chart->labels($date_arr);
      //this array will be for storing the hours that this employee has for this project within the time window (31 days) 
      $employee_arr = array(); 
     
        //we only need the name for the charting, but we need email to access the timesheet 
        $email = $employee_emails[$name]; 
        //this is technically a "collection" of timesheets, but there's only 1. 
        //we want the 0th element of the collection 
        $timesheet = Timesheet::where('user', $email)->get()[0];
        $timesheet_codes = $timesheet['Codes'];
        $total_hours_in_period = 0;
        $non_billable_hours = 0;
        $project_count = 0;
        $options = [];
        $options['scales']['xAxes'][]['stacked'] = true;
        $options['scales']['yAxes'][]['stacked'] = true;
        $options['legend']['labels']['boxWidth'] = 10;
        $options['legend']['labels']['padding'] = 6;
        foreach($codes_arr as $code) {
          //if ($code =="CEG" or $code =="CEGTRNG" or $code =="CEGMKTG" or $code =="CEGEDU") {
            //continue;
          //}
        if (in_array($code, array_keys($timesheet_codes))) {
          //note, we're just getting the first description.  for most drafters, I'm assuming this will be fine,
          //but note that this is a limitation at the moment. 
          $index = array_search($code,array_keys($timesheet_codes));
          $projectNames = array_values($timesheet_codes)[$index];
          $i = 0;
          foreach($projectNames as $project_hours){
            $names = array_keys($projectNames);
            $projectName = $names[$i];
              $i++;
             if($projectName == "Holiday" || $projectName == "PTO"){
              continue;
            }
            //this will store the time from this date range as a kvp ('date' => 'hours') 
            $project_hours_in_date_range = array();
            foreach($date_arr as $day) {
              if (in_array($day, array_keys($project_hours))) {
                $hours = $project_hours[$day];
              }
              else {
                $hours = 0;
              }
              $project_hours_in_date_range[$day] = $hours;
            }
            //If a project has no hours in the period, then don't add it to the chart.
            $code_total = 0;
            if($filter_all == true){
              foreach($project_hours_in_date_range as $date){
                $code_total = $code_total + $date;
              }
              $total_hours_in_period = $total_hours_in_period + $code_total;
              if($code =="CEG" or $code =="CEGTRNG" or $code =="CEGMKTG" or $code =="CEGEDU"){
                $non_billable_hours = $non_billable_hours + $code_total;
                if(isset($options[$code])){
                  $options[$code] = $options[$code] + $code_total;
                }
                else{
                  $options[$code] = $code_total;
                }
              }
              if($code_total <= 0){
                continue;
              }
            }
            else{
              foreach($project_hours_in_date_range as $date){
                $code_total = $code_total + $date;
              }
              $total_hours_in_period = $total_hours_in_period + $code_total;
              if($code =="CEG" or $code =="CEGTRNG" or $code =="CEGMKTG" or $code =="CEGEDU"){
                $non_billable_hours = $non_billable_hours + $code_total;
                if(isset($options[$code])){
                  $options[$code] = $options[$code] + $code_total;
                }
                else{
                  $options[$code] = $code_total;
                }
                continue;
              }
              if($code_total <= 0){
                continue;
              }
            }
            //////
            $project_count++;
            $chart->dataset($projectName, 'bar', array_values($project_hours_in_date_range))->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'backgroundColor'=>$fill_colors[$c_color_loop], 'fill' => true, 'hidden' => false]);
            $options['percent_billable'] = round((($total_hours_in_period - $non_billable_hours) / $total_hours_in_period) * 100);
            $options['projectcount'] = $project_count;
            $chart->options($options);
            
            //now, set the project hours in the date range as the value for this employee's name
            $employee_arr[$name] = $project_hours_in_date_range;
            $c_color_loop++;
            if($c_color_loop > $color_max){
              $c_color_loop = 0;
            }
          }
        }
        else {
          continue;
        }
      }
      //now, set the employee array for the code
      $all_data_arr[$code] = $employee_arr;
      //put the chart in the charts array
      //note, we don't push if the chart has no dataset,  e.g. if no employees had hours for this period
      if(!empty($chart->datasets)) {
        array_push($charts, $chart);
      } 
      $c_color_loop = 0;
    }      
    return view('pages.drafterhours', compact('charts', 'filter_all'));     
  }

  /**
   * Makes hours graph for all employees and employment grouping.
   * @param $request - Request variable with attributes to be assigned to $project.
   * @return array contains labels and dateset
   */
  public function hours_graph(Request $request) 
  {
    //variable to determine if this is the drafers' hours page or everyone's
    //if this is false, then this means we're coming from the "hours by project" link, and want everyone's hours
    //if tihs is true, then we are coming from the drafter hours page, and only want drafter hours for the past month (daily) 
    #$drafter_page = true; 
    
    //if (!isset($request['switch_chart_button'])) {//This is a button to toggle whether hours or dollars is displayed in the graph.  
    if (!isset($request['toggle_dollars'])) {//This is a button to toggle whether hours or dollars is displayed in the graph.  
      $chart_units = 'hours';
    } 
    else { 
      $chart_units = 'dollars';
    }

    if (!isset($request['toggle_all'])) {
      $filter_all = false;
    }
    else{
      $filter_all = true;
    }

    $project_grand_total = 0;
    $employeeLIST = $this->get_employee_list(null);
    $groupLIST = array("senior","project","SCADA","drafter","interns-admin","blank");

    $choosen_line_colors = array('#396AB1','#DA7C30','#3E9651','#CC2529','#535154','#6B4C9A','#922428','#948B3D','#488f31','#58508d','#bc5090','ff6361','#ffa600','#7BEEA5','#127135','#008080','#1AE6E6');
    $c_color_loop = 0;

    $group_colors = array('#4d2f14','#8D5524','#C68642','#F1C27D','#ffe9cc');
    $c_group_colors = 0;
    $projects = Project::whereRaw(['$and' => array(['projectcode' => ['$ne' => null]], ['hours_data' => ['$exists' => 'true']])])->get()->sortBy('projectname');

    //All the data for the graph is computed from this seciton, 
    //$labels, $dataset, $individual_dataset, $individual_dataset_monies, $project_grand_total, $dollarvalueinhouse, $dateenergization, $group_dataset, $group_dataset_monies,$previous_month_project_hours, $total_project_dollars, $previous_month_project_monies, $total_project_monies_per_month_dataset, $total_project_hours_per_month_dataset, $last_bill_amount, $last_bill_month
    function get_chart_info($projectnamevar, $employeeLIST, $groupLIST) {
      $selected_project = Project::where('projectname', $projectnamevar)->first();

      if ($selected_project) { #get all hours data for project
        $hours_data = $selected_project['hours_data'];
        $years = ! empty($hours_data) ? array_keys($hours_data) : [];
        asort($years);
        $hours_arr = array();
        $labels_arr = array();
        $group_project_hours_arr[0]=array();
        $group_project_hours_arr[1]=array();
        $group_project_hours_arr[2]=array();
        $group_project_hours_arr[3]=array();
        $group_project_hours_arr[4]=array();
        $group_project_hours_arr[5]=array(0); //PLEASE NOTE, A ZERO IS NEEDED AT THE END OF ARRAY THAT IS UNUSED, OTHERWISE A GET AN OFFSET ERROR, NOT SURE WHY
        $total_project_hours_per_month_arr=array();

        $group_project_monies_arr[0]=array();
        $group_project_monies_arr[1]=array();
        $group_project_monies_arr[2]=array();
        $group_project_monies_arr[3]=array();
        $group_project_monies_arr[4]=array();
        $group_project_monies_arr[5]=array(0); //PLEASE NOTE, A ZERO IS NEEDED AT THE END OF ARRAY THAT IS UNUSED, OTHERWISE A GET AN OFFSET ERROR, NOT SURE WHY
        $total_project_monies_per_month_arr=array();

        $previous_month_project_hours=0;
        $previous_month_project_monies=0;

        $previous_month = date('F', strtotime('-1 month'));
        $current_year = date('Y');
        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++) {
          $individual_project_hours_arr[$emp_count] = array();
          $individual_project_monies_arr[$emp_count] = array();
        }

        foreach($years as $year) {
          $year_hours_data = $hours_data[$year];
          $months = array_keys($year_hours_data);
          foreach($months as $month) {
            array_push($labels_arr, $month . '-' . $year);
            $people_hours = $year_hours_data[$month];
            
            $total_project_hours = $people_hours['Total'];
            array_push($hours_arr, $total_project_hours);
            $total_individual_hours[0]=0;
            $total_individual_hours[1]=0;
            $total_individual_hours[2]=0;
            $total_individual_hours[3]=0;
            $total_individual_hours[4]=0; 

            $total_individual_monies[0]=0;
            $total_individual_monies[1]=0;
            $total_individual_monies[2]=0;
            $total_individual_monies[3]=0;
            $total_individual_monies[4]=0; 

            for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++) {
              if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                $individual_project_hours[$emp_count] = 0;  //need to fix soon
                $individual_project_monies[$emp_count] = 0;  //need to fix soon
              } else {
                $individual_project_hours[$emp_count] = $people_hours[$employeeLIST[$emp_count][0]];  //need to fix soon
                $individual_project_monies[$emp_count] = $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];  //need to fix soon
              }
              array_push($individual_project_hours_arr[$emp_count], $individual_project_hours[$emp_count]);  
              array_push($individual_project_monies_arr[$emp_count], $individual_project_monies[$emp_count]);  

              if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                if ($month == $previous_month AND $current_year==$year) {
                  $previous_month_project_hours = $previous_month_project_hours + 0;
                }

                if ($month == $previous_month AND $current_year==$year) {
                  $previous_month_project_monies = $previous_month_project_monies + 0;
                }
              } else {
                if ($month == $previous_month AND $current_year==$year) {
                  $previous_month_project_hours = $previous_month_project_hours + $people_hours[$employeeLIST[$emp_count][0]];
                }

                if ($month == $previous_month AND $current_year==$year) {
                  $previous_month_project_monies = $previous_month_project_monies + $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                }
              }
              switch ($employeeLIST[$emp_count][2]) {
                case "senior":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[0]=$total_individual_hours[0]+0;
                    $total_individual_monies[0]=$total_individual_monies[0]+0;
                  } else {
                    $total_individual_hours[0]=$total_individual_hours[0]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[0]=$total_individual_monies[0]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                  }
                  break;
                case "project":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[1]=$total_individual_hours[1]+0;
                    $total_individual_monies[1]=$total_individual_monies[1]+0;
                  } else {
                    $total_individual_hours[1]=$total_individual_hours[1]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[1]=$total_individual_monies[1]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                  }
                  break;
                case "SCADA":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[2]=$total_individual_hours[2]+0;
                    $total_individual_monies[2]=$total_individual_monies[2]+0;
                  } else {
                    $total_individual_hours[2]=$total_individual_hours[2]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[2]=$total_individual_monies[2]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                  }
                  break;
                case "drafting":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[3]=$total_individual_hours[3]+0;
                    $total_individual_monies[3]=$total_individual_monies[3]+0;
                  } else {
                    $total_individual_hours[3]=$total_individual_hours[3]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[3]=$total_individual_monies[3]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                  }
                  break;
                case "interns-admin":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[4]=$total_individual_hours[4]+0;
                    $total_individual_monies[4]=$total_individual_monies[4]+0;
                  } else {
                    $total_individual_hours[4]=$total_individual_hours[4]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[4]=$total_individual_monies[4]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][1];
                  }
                  break;
                default:
                  break;
              } //end of the switch 
            } //end of the for loop going through all employees
            array_push($group_project_hours_arr[0], $total_individual_hours[0]);
            array_push($group_project_hours_arr[1], $total_individual_hours[1]);
            array_push($group_project_hours_arr[2], $total_individual_hours[2]);
            array_push($group_project_hours_arr[3], $total_individual_hours[3]);
            array_push($group_project_hours_arr[4], $total_individual_hours[4]);

            array_push($group_project_monies_arr[0], $total_individual_monies[0]);
            array_push($group_project_monies_arr[1], $total_individual_monies[1]);
            array_push($group_project_monies_arr[2], $total_individual_monies[2]);
            array_push($group_project_monies_arr[3], $total_individual_monies[3]);
            array_push($group_project_monies_arr[4], $total_individual_monies[4]);

            $total_project_monies_per_month = $total_individual_monies[0] + $total_individual_monies[1] + $total_individual_monies[2] + $total_individual_monies[3] + $total_individual_monies[4];
            array_push($total_project_monies_per_month_arr, $total_project_monies_per_month);  

            $total_project_hours_per_month = $total_individual_hours[0] + $total_individual_hours[1] + $total_individual_hours[2] + $total_individual_hours[3] + $total_individual_hours[4];
            array_push($total_project_hours_per_month_arr, $total_project_hours_per_month);  
            //$hours_arr=$total_project_monies_per_month_arr; //may want to delete later
          } //end of the forach loop for months
        } //end of the foreach loop for years


        $total_project_dollars = array_sum($group_project_monies_arr[0]) + array_sum($group_project_monies_arr[1]) + array_sum($group_project_monies_arr[2]) + array_sum($group_project_monies_arr[3]) + array_sum($group_project_monies_arr[4]);

        $project_grand_total  =  (array_sum($hours_arr));
        $dollarvalueinhouse = $selected_project['dollarvalueinhouse'];
        $dateenergization = $selected_project['dateenergization'];
        $last_bill_month="";
        $last_bill_amount="";
        $billing_data = $selected_project['bill_amount'];

        if (!empty($billing_data)) {
          $years = array_keys($billing_data);
          
          foreach($years as $year) {
            $years_billing_data = $billing_data[$year];
            $months = array_keys($years_billing_data);
            foreach($months as $month) {
              $last_bill_amount=$years_billing_data[$month];
            }
          }
        }

        $hours_array_filtered_reversed = array_reverse($hours_arr);
        $hours_array_filtered = array_filter($hours_arr);
        
        $countzeros=0;
        $numdetectflag=0;
        foreach ($hours_array_filtered_reversed as $var) {
          if ($var == 0 && $numdetectflag == 0) {
            $countzeros=$countzeros+1;
          } else {
            $numdetectflag=1;
            continue;
          }
        }


        $start_key = key($hours_array_filtered);
        end($hours_array_filtered);
        $end_key = key($hours_array_filtered)+$countzeros; //countzeros makes sure to count the ending zeros and add those back in, or the data will be off by how many months end with zero time
        

        //array_slice returns the sequence  of elements from the array array as specified by the offset and length parameters, basically trying to skip all the zeros
        $hours_arr_start_end = array_slice($hours_arr, $start_key, $end_key - $start_key + 1);
        $labels_arr_start_end = array_slice($labels_arr, $start_key, $end_key - $start_key + 1);
        
        $labels = $labels_arr_start_end;
        $dataset = array(' Total Hours', 'line', $hours_arr_start_end);

        //doing this for each individual employee's hours
        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++) {
          $individual_project_hours_arr_start_end[$emp_count] = array_slice($individual_project_hours_arr[$emp_count], -count($labels),count($labels)); $individual_dataset[$emp_count] = array($employeeLIST[$emp_count][0] . ' Hours', 'line', $individual_project_hours_arr_start_end[$emp_count]); 

          $individual_project_monies_arr_start_end[$emp_count] = array_slice($individual_project_monies_arr[$emp_count], -count($labels),count($labels));
          $individual_dataset_monies[$emp_count] = array($employeeLIST[$emp_count][0] . ' Dollars', 'line', $individual_project_monies_arr_start_end[$emp_count]);
        } 

        //doing this for group employee's hours, same as previous
        for ($group_count=0; $group_count<count($groupLIST); $group_count++) {
          $group_project_hours_arr_start_end[$group_count] = array_slice($group_project_hours_arr[$group_count], -count($labels),count($labels));
          $group_dataset[$group_count] = array($groupLIST[$group_count] . ' Hours', 'line', $group_project_hours_arr_start_end[$group_count]); 

          $group_project_monies_arr_start_end[$group_count] = array_slice($group_project_monies_arr[$group_count], -count($labels),count($labels));
          $group_dataset_monies[$group_count] = array($groupLIST[$group_count] . ' Dollars', 'line', $group_project_monies_arr_start_end[$group_count]); 
        }


        $total_project_hours_per_month_arr_start_end = array_slice($total_project_hours_per_month_arr, -count($labels),count($labels));
        $total_project_hours_per_month_dataset = array('Total Hours', 'line', $total_project_hours_per_month_arr_start_end);

        $total_project_monies_per_month_arr_start_end = array_slice($total_project_monies_per_month_arr, -count($labels),count($labels));
        $total_project_monies_per_month_dataset = array('Total Dollars', 'line', $total_project_monies_per_month_arr_start_end);
        return (array('labels' => $labels, 'dataset' => $dataset, 'title' => "{$selected_project['projectname']}, {$selected_project['projectcode']}, {$selected_project['projectmanager'][0]}", 'individual_dataset' => $individual_dataset, 'individual_dataset_monies' => $individual_dataset_monies, 'project_grand_total' => $project_grand_total, 'dollarvalueinhouse' => $dollarvalueinhouse, 'dateenergization' => $dateenergization, 'group_dataset' => $group_dataset, 'group_dataset_monies' => $group_dataset_monies,'previous_month_project_hours' => $previous_month_project_hours, 'total_project_dollars' => $total_project_dollars,'previous_month_project_monies' => $previous_month_project_monies, 'total_project_monies_per_month_dataset' => $total_project_monies_per_month_dataset, 'total_project_hours_per_month_dataset' => $total_project_hours_per_month_dataset, 'id' => "{$selected_project['id']}", 'last_bill_amount' => $last_bill_amount, 'last_bill_month' => $last_bill_month, 'billing_data' => $billing_data));
      } else {
        return Null;
      }
    } //get_chart_info
    $current_month = date('F');
    $previous_month = date('F', strtotime('-21 days'));
    $current_year = date('Y');
    $previous_year = date('Y', strtotime('-21 days'));
 
    //this filters out the projects we are going to actually make charts out of
    $non_zero_projects = Project::whereRaw([
      '$and' => array([
        'hours_data' => ['$exists' => 'true'],
        //'projectstatus' => ['$eq' => 'Won'],
        'projectstatus' => ['$nin' => ["Done and Billing Complete", "Expired"]],
        '$and' => array([
          "hours_data.{$previous_year}.{$previous_month}.Total"=> ['$exists' => true],  
          "hours_data.{$previous_year}.{$previous_month}.Total" =>['$ne'=>0]
        ])
      ])
    ])->get()->sortByDesc("hours_data.{$previous_year}.{$previous_month}.Total");
    $i=0;
    $i_max = count($non_zero_projects) . "<br>";

    //go through each project resulting from the filter directly above
    foreach($non_zero_projects as $non_zero_project) {
      $a = $non_zero_project['projectname'];
      if ($a =="CEG - General" or $a =="CEG Research and Training" or $a =="Education & Training" or $a =="CEG - Marketing" or $a == "NEEDS NAME") {
        continue;
      }
      $chart_info = get_chart_info($non_zero_project['projectname'],$employeeLIST,$groupLIST);
      $chart_variable[$i]= $chart_info['project_grand_total'];
      $dollarvalueinhousearray[$i]= $chart_info['dollarvalueinhouse'];

      if($chart_units == 'hours'){
        $c_color_loop=0;
        $chart[$i] = new HoursChart;
        $chart[$i]->title($chart_info['title']);
        $chart[$i]->labels($chart_info['labels']);
        $chart[$i]->dataset($chart_info['total_project_hours_per_month_dataset'][0], $chart_info['total_project_hours_per_month_dataset'][1], $chart_info['total_project_hours_per_month_dataset'][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false])->dashed([3]);

        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++) {
          if ($c_color_loop==count($choosen_line_colors)) { 
            $c_color_loop=0;
          }
          if ( array_sum($chart_info['individual_dataset'][$emp_count][2]) <> 0) {
            $chart[$i]->dataset($chart_info['individual_dataset'][$emp_count][0], $chart_info['individual_dataset'][$emp_count][1], $chart_info['individual_dataset'][$emp_count][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false]);
          }
          $c_color_loop=$c_color_loop+1;
        }
        $c_color_loop=0;

        for ($group_count=0; $group_count<(count($groupLIST)-1); $group_count++) {//count($groupLIST)
          if ( array_sum($chart_info['group_dataset'][$group_count][2]) <> 0) {
            $chart[$i]->dataset($chart_info['group_dataset'][$group_count][0], $chart_info['group_dataset'][$group_count][1], $chart_info['group_dataset'][$group_count][2])->options(['borderColor'=>$group_colors[$group_count], 'fill' => False, 'hidden' => true]);
          }
        }

        $chart[$i]->options([ 'dateenergization'              => $this->dateToStr($chart_info['dateenergization']) ]);
        $chart[$i]->options([ 'dollarvalueinhouse'            => $chart_info['dollarvalueinhouse'] ]);
        $chart[$i]->options([ 'CEGtimespenttodate'            => $chart_info['project_grand_total'] ]);
        $chart[$i]->options([ 'total_project_dollars'         => $chart_info['total_project_dollars'] ]);
        $chart[$i]->options([ 'previous_month_project_monies' => $chart_info['previous_month_project_monies'] ]);
        $chart[$i]->options([ 'previous_month_project_hours'  => $chart_info['previous_month_project_hours'] ]);
        $chart[$i]->options([ 'id'                            => $chart_info['id'] ]); 
        $chart[$i]->options([ 'last_bill_month'               => $chart_info['last_bill_month'] ]);
        $chart[$i]->options([ 'last_bill_amount'              => $chart_info['last_bill_amount'] ]);    
        $chart[$i]->options([ 'billing_data'                  => $chart_info['billing_data'] ]);
        $chart[$i]->options([ 'tooltip'                       => [ 'visible' => true ] ]);
      }else{
        //This section creates chart_dollars from chart_info
        $c_color_loop=0;
        $chart[$i] = new HoursChart;
        $chart[$i]->title($chart_info['title']);
        $chart[$i]->labels($chart_info['labels']);
        $chart[$i]->dataset($chart_info['total_project_monies_per_month_dataset'][0], $chart_info['total_project_monies_per_month_dataset'][1], $chart_info['total_project_monies_per_month_dataset'][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false])->dashed([3]);

        $c_color_loop=0;
        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++) {
          if ($c_color_loop==count($choosen_line_colors)) {
            $c_color_loop=0; 
          }
          if ( array_sum($chart_info['individual_dataset_monies'][$emp_count][2]) <> 0) {
            $chart[$i]->dataset($chart_info['individual_dataset_monies'][$emp_count][0], $chart_info['individual_dataset_monies'][$emp_count][1], $chart_info['individual_dataset_monies'][$emp_count][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false]);
          }

          $c_color_loop=$c_color_loop+1;
        }

        for ($group_count=0; $group_count<(count($groupLIST)-1); $group_count++) {
          if ( array_sum($chart_info['group_dataset'][$group_count][2]) <> 0) {
            $chart[$i]->dataset($chart_info['group_dataset_monies'][$group_count][0], $chart_info['group_dataset_monies'][$group_count][1], $chart_info['group_dataset_monies'][$group_count][2])->options(['borderColor'=>$group_colors[$group_count], 'fill' => False, 'hidden' => true]);
          }
        }

        $chart[$i]->options([ 'dateenergization'              => $this->dateToStr($chart_info['dateenergization']) ]);
        $chart[$i]->options([ 'dollarvalueinhouse'            => $chart_info['dollarvalueinhouse'] ]);
        $chart[$i]->options([ 'CEGtimespenttodate'            => $chart_info['project_grand_total'] ]);
        $chart[$i]->options([ 'total_project_dollars'         => $chart_info['total_project_dollars'] ]);
        $chart[$i]->options([ 'previous_month_project_monies' => $chart_info['previous_month_project_monies'] ]);
        $chart[$i]->options([ 'previous_month_project_hours'  => $chart_info['previous_month_project_hours'] ]);
        $chart[$i]->options([ 'id'                            => $chart_info['id'] ]); 
        $chart[$i]->options([ 'last_bill_month'               => $chart_info['last_bill_month'] ]);
        $chart[$i]->options([ 'last_bill_amount'              => $chart_info['last_bill_amount'] ]); 
        $chart[$i]->options([ 'billing_data'                  => $chart_info['billing_data'] ]);
        $chart[$i]->options([ 'tooltip'                       => [ 'visible' => true ] ]);
        }
        $i++;
    }      
    return view('pages.hoursgraph', compact('projects', 'chart', 'chart_variable','dollarvalueinhousearray','chart_units','filter_all'));
  }

/**************** Start of the Project Planner or Sticky Note Application *********************/

   /**
   * Opens a page displaying all projects that are Won.
   * @return view 'pages.planner'
   */ 
  public function planner(Request $request){
    $projects = Project::all()->where('projectstatus', 'Won');
    //sorts projects based on their closest due date to the current date
    $projects = $this->sort_by_closest_date($projects);
    //These variables are used for the searching and sorting tools on the planner page
    $search = $request['search'];
    $term = $request['sort'];
    $invert = $request['invert'];
    //$copy is set if the user had hit the copy button on the planner page
    $copy = $request['copyproject'];
    if(!is_null($copy)){
      $copy = Project::find($request['copyproject']);
    }

    if(isset($search) || (isset($term) && $term != "Closest Due Date")){
      //calls planner_search method to filter through projects
      $projects = $this->planner_search($search, $term, $invert);
    }
    foreach($projects as $project){
      //calls format_due_dates so the due dates are displayed as Y-M-D format
      $this->format_due_dates($project);
    }
    return view('pages.planner', compact('projects','term', 'search', 'invert', 'copy'));
  }

   /**
   * Sorts projects based on its due date that is closest to the present date
   * @return $sortedprojects the sorted list of all Won projects
   */ 
  public function sort_by_closest_date($projects){
    $today = date("Y-m-d");
    $alldates = array();
    $sortedprojects = array();
    //loops through each project and gets all of the second-level due dates from each project
      foreach($projects as $project){
        $name = $project['projectname'];
        $duedates = $project['duedates'];
        //checks if any duedates are saved, and adds any second level due dates that were entered
        if(isset($duedates)){
          $dates = [$project['dateenergization']];
          if (isset($duedates['physical'])){
            array_push($dates, $duedates['physical']['due']);
          }
          if (isset($duedates['control'])){
            array_push($dates, $duedates['control']['due']);
          }
          if (isset($duedates['collection'])){
            array_push($dates, $duedates['collection']['due']);
          }
          if (isset($duedates['transmission'])){
            array_push($dates, $duedates['transmission']['due']);
          }
          if (isset($duedates['scada'])){
            array_push($dates, $duedates['scada']['due']);
          }
          if (isset($duedates['studies'])){
            array_push($dates, $duedates['studies']['due']);
          }
          sort($dates);
          //finds the first date out of the sorted dates that isn't in the past, and saves it to the variable $earliestdate
          foreach($dates as $date){
            if($this->dateToStr($date, null) > $today){
              $earliestdate = $date;
              break;
            }
          }
        }
        else{
          //if a project has no other due dates, the date of energization will be its earliest date, as long as it's not in the past
          if($this->dateToStr($project['dateenergization'], null) > $today){
            $earliestdate = $project['dateenergization'];
          }
          else{
            $earliestdate = "None";
          }
        }
        $alldates[$name] = $earliestdate;
      }
      asort($alldates);
      /*
      Loops through $alldates, which is a sorted list of every project's earliest date.
      In this loop, it finds the project associated with that date and appends the entire project to the $sortedprojects variable.
      That way instead of having just the dates sorted, it has the entire projects sorted and ready to display
      */
      foreach($alldates as $key => $value){
        foreach ($projects as $project){
          if ($project['projectname'] == $key){
            array_push($sortedprojects, $project);
            break;
          }
        }
      }
    return $sortedprojects;
  }

    /**
   * Search method for the planner page that searches and sorts through only won projects.
   * @return $projects
   */ 
  public function planner_search($search_term, $sort_term, $invert)
  {
    $won_projects = Project::where(array(['projectstatus', 'Won']));
    //checks whether or not to invert the order to display the projects
    if(isset($invert))
    {
      $asc_desc = 'desc';
    }
    else
    {
      $asc_desc = 'asc';
    }
    //checks if a search term was entered
    if (isset($search_term)) {
      //if sort term isn't closest due date, it filters out by the search term and orders them by the sort term
      if (isset($sort_term) && $sort_term != "Closest Due Date"){
        $projects = $won_projects->where('projectname', 'regexp', "/$search_term/i")
                    ->orderBy($sort_term, $asc_desc)
                    ->get();             
      }
      else {
        //if sort term is closest due date, it filters by the search term and leaves the order as is
        $projects = $won_projects->where('projectname', 'regexp', "/$search_term/i")
                    ->get();
    }
    }
    //if no search term, orders all projects by sort term and acending or descending
    else {
      $projects = $won_projects->orderBy($sort_term, $asc_desc)->get();
    }
    //calls displayFormat method that formats certain dates in the projects
    foreach ($projects as $project) {
      $project = $this->displayFormat($project);
    }
    return $projects;

  }

  /**
   * Sets all the due dates of the copied project into the pasted project
   * @return redirect 'pages.manage_project'
   */ 
  public function paste_dates(Request $request){
    //gets the projects that you hit copy on and paste on
    $copyproject = Project::find($request->get('copyproject'));
    $pasteproject = Project::find($request->get('pasteproject'));
    //resets all of the paste project's due dates and sets them equal to the copied project
    $pasteproject['duedates'] = array();
    $pasteproject['duedates'] = $copyproject['duedates'];
    $pasteid = $pasteproject['id'];
    $pasteproject->save();
    return redirect('/manageproject/'.$pasteid);
  }

   /**
   * Finds the specific project that you want to manage due dates.
   * @return view 'pages.manage_project'
   */ 
  public function manage_project($id){
    $project = Project::find($id);
    //sets field number variables to 0 if the project hasn't saved any due dates yet
    if(!isset($project['duedates'])){
      $totalstudies = 0;
      $transmissionfields = 0;
      $collectionfields = 0;
      $controlfields = 0;
      $physicalfields = 0;
      $scadafields = 0;
      $communicationfields = 0;
      $miscfields = 0;
    }
    else{
      //sets duedates variable as the duedates category for the current project being managed
      $duedates = $project['duedates'];
      //counts the number of third-level studies if the second-level field exists
      if (isset($duedates['studies'])){
        $totalstudies = 0;
        //sets $studies equal to the studies category for the current project. studies is a subcategory of duedates
        $studies = $duedates['studies'];
        //keys are the names given to the values stored inside studies
        $keys = array_keys($studies);
        //loops through all keys and increments the total studies if isn't a person or due date, since those are the base study person and due date
        foreach($keys as $key){
          if ($key != "person1" && $key != "due"){
            $totalstudies++;
          }
        }
      }
      else{
        $totalstudies = null;
      }
     //counts the number of third-level transmission fields if the second-level field exists
      if (isset($duedates['transmission'])){
        $transmissionfields = 0;
        $transmissions = $duedates['transmission'];
        $keys = array_keys($transmissions);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $transmissionfields++;
          }
        }
      }
      else{
        $transmissionfields = null;
      }
      //counts the number of third-level collection fields if the second-level field exists
      if (isset($duedates['collection'])){
        $collectionfields = 0;
        $collections = $duedates['collection'];
        $keys = array_keys($collections);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $collectionfields++;
          }
        }
      }
      else{
        $collectionfields = null;
      }
      //counts the number of third-level control fields if the second-level field exists
      if (isset($duedates['control'])){
        $controlfields = 0;
        $controls = $duedates['control'];
        $keys = array_keys($controls);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $controlfields++;
          }
        }
      }
      else{
        $controlfields = null;
      }
      //counts the number of third-level physical fields if the second-level field exists
      if (isset($duedates['physical'])){
        $physicalfields = 0;
        $physicals = $duedates['physical'];
        $keys = array_keys($physicals);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $physicalfields++;
          }
        }
      }
      else{
        $physicalfields = null;
      }
      //counts the number of third-level SCADA fields if the second-level field exists, as well as fourth-level fields under communication
    if (isset($duedates['scada'])){
      $scadafields = 0;
      $communicationfields = 0;
      $scada = $duedates['scada'];
      $keys = array_keys($scada);
      foreach($keys as $key){
        if ($key != "person1" && $key != "person2" && $key != "due"){
          $scadafields++;
          //comminication has its own subcategories, so it has its own loop and variable to count its rows
          if($key == 'Communication'){
            $comkeys = array_keys($scada['Communication']);
            foreach($comkeys as $comkey){
              if ($comkey != "person1" && $comkey != "person2" && $comkey != "due"){
                $communicationfields++;
              }
            }
          }
        }
      }
    }
    else{
      $scadafields = null;
      $communicationfields = null;
    }
    //counts the number of third-level additional fields. No matter what second-level name they are under
    if (isset($duedates['additionalfields'])){
      $miscfields = 0;
      $additionalfields = $duedates['additionalfields'];
      foreach($additionalfields as $additionalfield){
        $fieldkeys = array_keys($additionalfield);
        foreach($fieldkeys as $fk){
          if ($fk != "person1" && $fk != "person2" && $fk != "due"){
            $miscfields++;
          }
        }
      }
    }
    else{
      $miscfields = null;
    }
    
  }
    //formats the due dates to be displayed in the forms if there are any saved
    $project = $this->format_due_dates($project);
    return view('pages.manage_project', compact('project', 'totalstudies', 'transmissionfields', 'collectionfields', 'controlfields', 'physicalfields', 'scadafields', 'communicationfields', 'miscfields'));
  }

  /**
   * Changes or adds specific due dates from the manage project form
   * @param $request - Request variable with attributes to be assigned to $project.
   * @param $id - the unique id of the project to be updated.
   * @return redirect /planner
   */ 
  public function edit_due_dates(Request $request, $id)
  {
    //finds individual project being edited and calls the store_dates helper method on it
    $project = Project::find($id);
    $this->store_dates($project, $request);
    return redirect('/planner')->with('Success!', 'Project has been successfully updated');
  }

    /**
   * Stores the given manage project data into the database
   * @param $project - variable type Project to be saved to the database.
   * @param $req - Request variable with attributes to be assigned to $project.
   */
  protected function store_dates($project, $req)
  {
      $duedates = array();
      //stores all of the people and due dates for all studies fields
      if (!is_null($req->get('totalstudies'))){
        //gets the values of the second-level or non-indented fields
        $studies = array();
        $studies['person1'] = $req->get('studiesperson1');
        $studies['due'] = $this->strToDate($req->get('studiesdue'),null);
        //loops through all third-level or indented studies, and if the study exists, it gets the values for every third-level study
        for($i = 1; $i <= $req->get('totalstudies'); $i++){
          $studyname = $req->get('study'.$i.'name');
          if ($studyname == null){
            continue;
          }
          $studies[$studyname] = array();
          $studies[$studyname]['person1'] = $req->get('study'.$i.'person1');
          $studies[$studyname]['due'] = $this->strToDate($req->get('study'.$i.'due'), null);
        }
        $duedates['studies'] = $studies;
      }

      //stores all of the people and due dates for all physical fields
      if (!is_null($req->get('physicalfields'))){
        //gets the values of the second-level or non-indented fields
        $physicals = array();
        $physicals['person1'] = $req->get('physicalperson1');
        $physicals['person2'] = $req->get('physicalperson2');
        $physicals['due'] = $this->strToDate($req->get('physicaldue'),null);
        //loops through all third-level or indented physical categories, and if the name exists, it gets the values for every third-level
        for($i = 1; $i <= $req->get('physicalfields'); $i++){
          $physicalname = $req->get('physical'.$i.'name');
          if ($physicalname == null){
            continue;
          }
          $physicals[$physicalname] = array();
          $physicals[$physicalname]['person1'] = $req->get('physical'.$i.'person1');
          $physicals[$physicalname]['person2'] = $req->get('physical'.$i.'person2');
          $physicals[$physicalname]['due'] = $this->strToDate($req->get('physical'.$i.'due'), null);
        }
        $duedates['physical'] = $physicals;
      }

      //stores all of the people and due dates for all control fields
      if (!is_null($req->get('controlfields'))){
        //gets the values of the second-level or non-indented fields
        $controls = array();
        $controls['person1'] = $req->get('controlperson1');
        $controls['person2'] = $req->get('controlperson2');
        $controls['due'] = $this->strToDate($req->get('controldue'),null);
        //loops through all third-level or indented wiring and control categories, and if the name exists, it gets the values for every third-level
        for($i = 1; $i <= $req->get('controlfields'); $i++){
          $controlname = $req->get('control'.$i.'name');
          if ($controlname == null){
            continue;
          }
          $controls[$controlname] = array();
          $controls[$controlname]['person1'] = $req->get('control'.$i.'person1');
          $controls[$controlname]['person2'] = $req->get('control'.$i.'person2');
          $controls[$controlname]['due'] = $this->strToDate($req->get('control'.$i.'due'), null);
        }
        $duedates['control'] = $controls;
      }

      //stores all of the people and due dates for all collection fields
      if (!is_null($req->get('collectionfields'))){
        //gets the values of the second-level or non-indented fields
        $collections = array();
        $collections['person1'] = $req->get('collectionperson1');
        $collections['person2'] = $req->get('collectionperson2');
        $collections['due'] = $this->strToDate($req->get('collectiondue'),null);
        //loops through all third-level or indented collection categories, and if the name exists, it gets the values for every third-level
        for($i = 1; $i <= $req->get('collectionfields'); $i++){
          $collectionname = $req->get('collection'.$i.'name');
          if ($collectionname == null){
            continue;
          }
          $collections[$collectionname] = array();
          $collections[$collectionname]['person1'] = $req->get('collection'.$i.'person1');
          $collections[$collectionname]['person2'] = $req->get('collection'.$i.'person2');
          $collections[$collectionname]['due'] = $this->strToDate($req->get('collection'.$i.'due'), null);
        }
        $duedates['collection'] = $collections;
      }

      //stores all of the people and due dates for all transmission fields
      if (!is_null($req->get('transmissionfields'))){
        //gets the values of the second-level or non-indented fields
        $transmissions = array();
        $transmissions['person1'] = $req->get('transmissionperson1');
        $transmissions['person2'] = $req->get('transmissionperson2');
        $transmissions['due'] = $this->strToDate($req->get('transmissiondue'),null);
        //loops through all third-level or indented transmission categories, and if the name exists, it gets the values for every third-level
        for($i = 1; $i <= $req->get('transmissionfields'); $i++){
          $transmissionname = $req->get('transmission'.$i.'name');
          if ($transmissionname == null){
            continue;
          }
          $transmissions[$transmissionname] = array();
          $transmissions[$transmissionname]['person1'] = $req->get('transmission'.$i.'person1');
          $transmissions[$transmissionname]['person2'] = $req->get('transmission'.$i.'person2');
          $transmissions[$transmissionname]['due'] = $this->strToDate($req->get('transmission'.$i.'due'), null);
        }
        $duedates['transmission'] = $transmissions;
      }

      //stores all of the people and due dates for all SCADA fields
      if (!is_null($req->get('scadafields'))){
        //gets the values of the second-level or non-indented fields
        $scada = array();
        $scada['person1'] = $req->get('scadaperson1');
        $scada['person2'] = $req->get('scadaperson2');
        $scada['due'] = $this->strToDate($req->get('scadadue'),null);
        //loops through all third-level or indented SCADA categories, and if the name exists, it gets the values for every third-level
        for($i = 1; $i <= $req->get('scadafields'); $i++){
          $scadaname = $req->get('scada'.$i.'name');
          if ($scadaname == null){
            continue;
          }
          $scada[$scadaname] = array();
          $scada[$scadaname]['person1'] = $req->get('scada'.$i.'person1');
          $scada[$scadaname]['person2'] = $req->get('scada'.$i.'person2');
          $scada[$scadaname]['due'] = $this->strToDate($req->get('scada'.$i.'due'), null);
          //loops through all the subcategories under communication and gets their values, is fourth-level indented twice
          if($scadaname == 'Communication'){
            for($j = 1; $j <= $req->get('communicationfields'); $j++){
              $communicationname = $req->get('communication'.$j.'name');
              if ($communicationname == null){
                continue;
              }
              $scada[$scadaname][$communicationname] = array();
              $scada[$scadaname][$communicationname]['person1'] = $req->get('communication'.$j.'person1');
              $scada[$scadaname][$communicationname]['person2'] = $req->get('communication'.$j.'person2');
              $scada[$scadaname][$communicationname]['due'] = $this->strToDate($req->get('communication'.$j.'due'), null);
            }
          }
        }
        $duedates['scada'] = $scada;
      }

      //stores all of the people and due dates for all miscellaneous fields
      if (!is_null($req->get('total'))){
        //gets the values of the second-level or non-indented fields
        $additionalfields = array();
        for($i = 1; $i <= $req->get('total'); $i++){
          $namefield = $req->get('row'.$i.'name');
          if ($namefield == null){
            continue;
          }
          $additionalfields[$namefield] = array();
          $additionalfields[$namefield]['person1'] = $req->get('row'.$i.'person1');
          $additionalfields[$namefield]['person2'] = $req->get('row'.$i.'person2');
          $additionalfields[$namefield]['due'] = $this->strToDate($req->get('row'.$i.'due'), null);
          //loops through all third-level or indented Miscellaneous categories, and if the name exists, it gets the values for every third-level
          for($j = 1; $j <= $req->get('miscfields'); $j++){
            $subname = $req->get(''.$i.'misc'.$j.'name');
            if($subname == null){
              continue;
            }
            $additionalfields[$namefield][$subname] = array();
            $additionalfields[$namefield][$subname]['person1'] = $req->get(''.$i.'misc'.$j.'person1');
            $additionalfields[$namefield][$subname]['person2'] = $req->get(''.$i.'misc'.$j.'person2');
            $additionalfields[$namefield][$subname]['due'] = $this->strToDate($req->get(''.$i.'misc'.$j.'due'), null);
          }
        }
        $duedates['additionalfields'] = $additionalfields;
      }

    $project->duedates = $duedates;
    $project->save();
  }
  
  /**
   * Method used to format the due dates so that they can be displayed to the user on the manage project page
   * @param $project - the current project requesting to be managed.
   * @return $project - a modified version of projects so the dates can be displayed properly
   */
  protected function format_due_dates($project){
    $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    //changes due dates stored in the database into strings in order to be displayed properly
    if (isset($project['duedates'])){
      $duedates = $project['duedates'];
      //If studies are saved for this project, it will go through each study due date and convert it to a string
      if (isset($duedates['studies'])){
        $duedates['studies']['due'] = $this->dateToStr($project['duedates']['studies']['due']);
        $keys = array_keys($duedates['studies']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "due"){
            $duedates['studies'][$key]['due'] = $this->dateToStr($project['duedates']['studies'][$key]['due']);
          }
        }
      }

      //If any Transmission Line dates are saved for this project, it will go through each Transmission Line due date and convert it to a string
      if (isset($duedates['transmission'])){
        $duedates['transmission']['due'] = $this->dateToStr($project['duedates']['transmission']['due']);
        $keys = array_keys($duedates['transmission']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $duedates['transmission'][$key]['due'] = $this->dateToStr($project['duedates']['transmission'][$key]['due']);
          }
        }
      }

      //If any Collection Line dates are saved for this project, it will go through each Collection Line due date and convert it to a string
      if (isset($duedates['collection'])){
        $duedates['collection']['due'] = $this->dateToStr($project['duedates']['collection']['due']);
        $keys = array_keys($duedates['collection']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $duedates['collection'][$key]['due'] = $this->dateToStr($project['duedates']['collection'][$key]['due']);
          }
        }
      }

      //If any Wiring and Conrol dates are saved for this project, it will go through each Wiring and Conrol due date and convert it to a string
      if (isset($duedates['control'])){
        $duedates['control']['due'] = $this->dateToStr($project['duedates']['control']['due']);
        $keys = array_keys($duedates['control']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $duedates['control'][$key]['due'] = $this->dateToStr($project['duedates']['control'][$key]['due']);
          }
        }
      }

      //If any Physical dates are saved for this project, it will go through each Physical due date and convert it to a string
      if (isset($duedates['physical'])){
        $duedates['physical']['due'] = $this->dateToStr($project['duedates']['physical']['due']);
        $keys = array_keys($duedates['physical']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $duedates['physical'][$key]['due'] = $this->dateToStr($project['duedates']['physical'][$key]['due']);
          }
        }
      }

      //If any SCADA dates are saved for this project, it will go through each SCADA due date and convert it to a string
      if (isset($duedates['scada'])){
        $duedates['scada']['due'] = $this->dateToStr($project['duedates']['scada']['due']);
        $keys = array_keys($duedates['scada']);
        foreach($keys as $key){
          if ($key != "person1" && $key != "person2" && $key != "due"){
            $duedates['scada'][$key]['due'] = $this->dateToStr($project['duedates']['scada'][$key]['due']);
            //Communication is a subcategory under SCADA and has its own subcategories. This code loops through Communication subcategories and converts their due dates to strings
            if ($key == 'Communication'){
              $comkeys = array_keys($duedates['scada']['Communication']);
              foreach($comkeys as $comkey){
                if ($comkey != "person1" && $comkey != "person2" && $comkey != "due"){
                  $duedates['scada']['Communication'][$comkey]['due'] = $this->dateToStr($project['duedates']['scada']['Communication'][$comkey]['due']);
                }
              }
            }
          }
        }
      }
       
      //If any SCADA dates are saved for this project, it will go through each SCADA due date and convert it to a string
      if (isset($duedates['additionalfields'])){
      $additionalfields = $project['duedates']['additionalfields'];
      $keys = array_keys($additionalfields);
        foreach ($keys as $key){
          $additionalfields[$key]['due'] = $this->dateToStr($additionalfields[$key]['due']);
          $subkeys = array_keys($additionalfields[$key]);
          foreach($subkeys as $subkey){
            if ($subkey != "person1" && $subkey != "person2" && $subkey != "due"){
              $additionalfields[$key][$subkey]['due'] = $this->dateToStr($additionalfields[$key][$subkey]['due']);
            }
          }
        }
        $duedates['additionalfields'] = $additionalfields;
      }

      $project->duedates = $duedates;
    }
      
    return $project;
  }
    /**
   * Loads data for the Sticky Note Gantt chart. 
   * If a filter is set, then it will only display projects that fit in that filter.
   * @return view 'pages.sticky_note'
   */
  public function sticky_note(Request $request){
    //Gets all won projects and sorts them in order based on its closest due date to the present day
    $projects = Project::all()->where('projectstatus', 'Won');
    $projects = $this->sort_by_closest_date($projects);
    $json = [];
    $counter = 0;
    $term = $request['employeesearch'];
    $major = $request['secondlevelfilter'];
    $minor = $request['thirdlevelfilter'];
    //if any filter has been selected then filter by that category, employee, or deliverable
    if($term != null && $term != 'No Filter' || $major != null && $major != 'No Filter' || $minor != null && $minor != 'No Filter'){
      $today = date("Y-m-d");

      //filters by major deliverables
      if($major != null && $major != 'No Filter'){
        foreach($projects as $project){
          $show = false;
          if ($this->project_to_json($project) != null){
            $duedates = $project['duedates'];
            $majorkeys = array_keys($duedates);
            if(array_key_exists($major, $duedates)){
              foreach($majorkeys as $key){
                //If the major deliverable is not matching the filter category remove it so it won't appear in the gantt chart
                if($key != $major){
                  unset($duedates[$key]);
                }
                else{
                  //show the project if it's major deliverable's due date isn't in the past
                  if($this->dateToStr($duedates[$major]['due']) >= $today && $this->dateToStr($duedates[$major]['due']) != "None"){
                    $show = true;
                  }
                }
              }
              if($show == true){
                $project['duedates'] = $duedates;
                //adds the JSON converted project to the $json array
                $json[$counter] = $this->project_to_json($project);
                $counter++;
              }
            }
          }
        }
      }

      //filters by minor deliverables
      if($minor != null && $minor != 'No Filter'){
        foreach($projects as $project){
          $show = false;
          if ($this->project_to_json($project) != null){
            $duedates = $project['duedates'];
            $majorkeys = array_keys($duedates);
            $majorcount = 0;
            foreach($duedates as $duedate){
              //checks if the minor deliverable you're filtering by is in the current major deliverable
              if(array_key_exists($minor, $duedate)){
                $minorkeys = array_keys($duedate);
                foreach($minorkeys as $key){
                  //if the current minor deliverable is not equal to the filtered deliverable, then unset it so it won't appear on the gantt chart
                  if ($key != $minor && $key != 'person1' && $key != 'person2' && $key != 'due'){
                    unset($duedate[$key]);
                    $duedates[$majorkeys[$majorcount]] = $duedate;
                  }
                  else{
                    //if the due date for the minor deliverable isn't in the past, then display the project on the gantt chart
                    if($this->dateToStr($duedates[$majorkeys[$majorcount]][$minor]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]][$minor]['due']) != "None"){
                      $show = true;
                    }
                  }
                }
              }
              else{
                //If the minor deliverable is not within the major deliverable, then unset the major deliverable
                unset($duedates[$majorkeys[$majorcount]]);
              }
              $majorcount++;
            }
            if($show == true){
              $project['duedates'] = $duedates;
              $convertedproject = $this->project_to_json($project);
              $json[$counter] = $convertedproject;
              $counter++;
            }
          }
        }
      }

      //if the employee/category filter is equal to one of the following categories, it filters out any projects that don't involve employees with that job class
      if($term == 'SCADA' || $term == 'drafting' || $term == 'senior' || $term == 'project' || $term == 'interns-admin'){
        //gets a list of all the employee names that fit under the filtered category
        $filteredemployees = User::all()->where('jobclass', $term);
        $filterednames = [];
        foreach($filteredemployees as $filteredemployee){
          array_push($filterednames, $filteredemployee['name']);
        }
        foreach($projects as $project){
          $show = false;
          if ($this->project_to_json($project) != null){
            //If an employee in the filtered names is the project manager, then display the whole project
            if(in_array($project['projectmanager'][0], $filterednames)){
              $json[$counter] = $this->project_to_json($project);
              $counter++;
              continue;
            }
            $duedates = $project['duedates'];
            $majorkeys = array_keys($duedates);
            $majorcount = 0;
            foreach($duedates as $duedate){
              if(array_key_exists('person2', $duedate)){
                //if the major deliverable doesn't have any filtered employees
                if(!in_array($duedate['person1'], $filterednames) && !in_array($duedate['person2'], $filterednames)){
                  $minorkeys = array_keys($duedate);
                  $minorcount = 0;
                  $nonamecount = 0;
                  foreach($duedate as $task){
                    if($minorkeys[$minorcount] != 'person1' && $minorkeys[$minorcount] != 'person2' && $minorkeys[$minorcount] != 'due'){
                      //major deliverable doesn't involve any of the filtered employees, but a minor deliverable does, so show the project on the gantt chart
                      if(in_array($task['person1'], $filterednames) || in_array($task['person2'], $filterednames)){
                        if($this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) != "None"){
                          $show = true;
                        }
                      }
                      else{
                        //filter out the minor deliverable if it doesn't have the filtered employee in it
                        unset($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]);
                        $nonamecount++;
                      }
                    }
                    $minorcount++;
                  }
                  //remove the major deliverable if all of its minor deliverables were removed
                  if($minorcount - 3 == $nonamecount){
                    unset($duedates[$majorkeys[$majorcount]]);
                  }
                }
                else{
                  //show the major deliverable if one of the filtered employees is a part of it
                  if($this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) != "None"){
                    $show = true;
                  }
                }
              }
              //Does the same thing as above for studies because they don't have a person 2
              elseif($majorkeys[$majorcount] != "additionalfields"){
                if(!in_array($duedate['person1'], $filterednames)){
                    $minorkeys = array_keys($duedate);
                    $minorcount = 0;
                    $nonamecount = 0;
                    foreach($duedate as $task){
                      if($minorkeys[$minorcount] != 'person1' && $minorkeys[$minorcount] != 'due'){
                        if(in_array($task['person1'], $filterednames)){
                          if($this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) != "None"){
                            $show = true;
                          }
                        }
                        else{
                          unset($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]);
                          $nonamecount++;
                        }
                      }
                      $minorcount++;
                    }
                    if($minorcount - 2 == $nonamecount){
                      unset($duedates[$majorkeys[$majorcount]]);
                    }
                }
                else{
                  if($this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) != "None"){
                    $show = true;
                  }
                }
              }
              $majorcount++;
            }
            if($show == true){
              $project['duedates'] = $duedates;
              $convertedproject = $this->project_to_json($project);
              $json[$counter] = $convertedproject;
              $counter++;
            }
          }
        }
      }

      //If the employee/category filter is set to a particular employee, it goes through every project and only filters in the ones that the employee is a part of
      elseif($term != null && $term != 'No Filter'){
        foreach($projects as $project){
          $show = false;
          if ($this->project_to_json($project) != null){
            //if the filtered employee is the project manager for the current project, then show the whole project
            if($project['projectmanager'][0] == $term){
              $json[$counter] = $this->project_to_json($project);
              $counter++;
              continue;
            }
            $duedates = $project['duedates'];
            $majorkeys = array_keys($duedates);
            $majorcount = 0;
            foreach($duedates as $duedate){
              if(array_key_exists('person2', $duedate)){
                //if the major deliverable doesn't have the employee involved
                if($duedate['person1'] != $term && $duedate['person2'] != $term){
                  $minorkeys = array_keys($duedate);
                  $minorcount = 0;
                  $nonamecount = 0;
                  foreach($duedate as $task){
                    if($minorkeys[$minorcount] != 'person1' && $minorkeys[$minorcount] != 'person2' && $minorkeys[$minorcount] != 'due'){
                      //if the minor deliverable has the filtered employee and isn't in the past, then show the project
                      if($task['person1'] == $term || $task['person2'] == $term){
                        if($this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) != "None"){
                          $show = true;
                        }
                      }
                      else{
                        //remove the minor deliverable so it won't be seen on the gantt chart
                        unset($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]);
                        $nonamecount++;
                      }
                    }
                    $minorcount++;
                  }
                  //if all of the minor deliverables for a project were removed, then don't show the major deliverable
                  if($minorcount - 3 == $nonamecount){
                    unset($duedates[$majorkeys[$majorcount]]);
                  }
                }
                else{
                  //show the project if the major deliverable has the filtered employee
                  if($this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) != "None"){
                    $show = true;
                  }
                }
              }
              //repeats the same thing but for studies, since they don't have a second person saved
              elseif($majorkeys[$majorcount] != "additionalfields"){
                if($duedate['person1'] != $term){
                    $minorkeys = array_keys($duedate);
                    $minorcount = 0;
                    $nonamecount = 0;
                    foreach($duedate as $task){
                      if($minorkeys[$minorcount] != 'person1' && $minorkeys[$minorcount] != 'due'){
                        if($task['person1'] == $term){
                          if($this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]['due']) != "None"){
                            $show = true;
                          }
                        }
                        else{
                          unset($duedates[$majorkeys[$majorcount]][$minorkeys[$minorcount]]);
                          $nonamecount++;
                        }
                      }
                      $minorcount++;
                    }
                    if($minorcount - 2 == $nonamecount){
                      unset($duedates[$majorkeys[$majorcount]]);
                    }
                }
                else{
                  if($this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) >= $today && $this->dateToStr($duedates[$majorkeys[$majorcount]]['due']) != "None"){
                    $show = true;
                  }
                }
              }
              $majorcount++;
            }
            //if the project is to be shown, then set the duedates for that project to the filtered out version,
            //convert the project to JSON format, and add it to the $json variable
            if($show == true){
              $project['duedates'] = $duedates;
              $convertedproject = $this->project_to_json($project);
              $json[$counter] = $convertedproject;
              $counter++;
            }
          }
        }

      }
    }
    //if no filter is used, it calls the project_to_json helper method on every project and displays it if it doesn't return null
    else{
      //calls the project_to_json method for each project that has due dates saved
      foreach($projects as $project){
        if ($this->project_to_json($project) != null){
          $json[$counter] = $this->project_to_json($project);
          $counter++;
        }
      }
    }
    $filtered = false;
    return view('pages.sticky_note', compact('json', 'filtered', 'term', 'major', 'minor'));
  }

      /**
   * Loads a single employee's data for the Sticky Note Gantt chart.
   * This method is called when the "Load Your Projects" or "Load All Projects" buttons are pressed.
   * @return view 'pages.sticky_note'
   */
  public function employee_gantt(Request $request){
    //loads all projects if an individual's projects are currently loaded
    if($request->get('loaded') == 'your'){
      $projects = Project::all()->where('projectstatus', 'Won');
      $projects = $this->sort_by_closest_date($projects);
      $json = [];
      $counter = 0;
      //calls the project_to_json method for each project that has due dates saved
      foreach($projects as $project){
        if ($this->project_to_json($project) != null){
          $json[$counter] = $this->project_to_json($project);
          $counter++;
        }
      }
      $filtered = false;
    }
    //loads an individual's projects if all projects are currently loaded
    else{
      $projects = Project::all()->where('projectstatus', 'Won');
      $projects = $this->sort_by_closest_date($projects);
      $json = [];
      $counter = 0;
      //calls the project_to_json method for each project that has due dates saved, then adds projects that the current user is involved in
      foreach($projects as $project){
        if ($this->project_to_json($project) != null){
          if($project['projectmanager'][0] == auth()->user()->name){
            $json[$counter] = $this->project_to_json($project);
            $counter++;
            continue;
          } 
          $convertedproject = $this->project_to_json($project);
          for($i = 1; $i < sizeof($convertedproject); $i++){
            $decode = json_decode($convertedproject[$i], true);
            if(array_key_exists('name_2', $decode)){
              if ($decode['name_1'] == auth()->user()->name || $decode['name_2'] == auth()->user()->name){
                $json[$counter] = $this->project_to_json($project);
                $counter++;
              }
            }
            else{
              if ($decode['name_1'] == auth()->user()->name){
                $json[$counter] = $this->project_to_json($project);
                $counter++;
              }
            }
          }
        }
      }
      $filtered = true;
  }
  return view('pages.sticky_note', compact('json', 'filtered'));
  }

    /**
   * Turns information on the project from the database into a JSON format so the Gantt chart can display the information.
   * @param $project is the current project that's information is being converted to a JSON format.
   * @return $json an array conatining elements of the project in a JSON format.
   */
  public function project_to_json($project){
    if (isset($project['duedates'])){
      //used for color coding tasks later on
      $startweek = date("Y-m-d", strtotime('monday this week')); 
      $endweek = date("Y-m-d", strtotime('sunday this week'));
      // Sets initial parent folder for the project
      $duedates = $project['duedates'];
      $text = $project['projectname'];
      $today = date("Y-m-d");
      $energize = $this->dateToStr($project['dateenergization']);
      $projectmanager = $project['projectmanager'];
      //$parent is the array format for the parent folder
      $parent = array(
        "id" => "id_".$text, 
        "text" => $text, 
        "start_date" => $today,
        "end_date" => $energize,
        "name_1" => $projectmanager,
        "color" => "rgb(75, 220, 100, 0.4)"
      );
      //json_encode turns the $parent array into a JSON object
      $parent = json_encode($parent);
      $json = array();
      //adds the parent folder to the $json variable
      array_push($json, $parent);
      $keys = array_keys($duedates);
      $i = 0;
      //loops through each duedate in the project and adds them in a JSON format to $json variable
      foreach($duedates as $duedate){
        // Sets the additionalfields as a JSON format
        if($keys[$i] == 'additionalfields'){
          $addeddates = $duedates['additionalfields'];
          $addedkeys = array_keys($addeddates);
          $j = 0;
          //loops through each miscellaneous project, converts it to JSON, and adds it to the $json variable
          foreach($addeddates as $addeddate){
            $pname = $addedkeys[$j];
            $addedcount = $i + $j;
            $id = 'id_'.$addedcount.$project['projectname'];
            $start = $addeddate['due'];
            $start = $this->dateToStr($start);
            //If the task doesn't have a due date or is in the past, it will skip this iteration of the loop
            if($start == "None" || $start < $today){
              $j++;
              continue;
            }
            $end = new \DateTime($this->dateToStr($start));
            $end = $end->add(new DateInterval('P1D'));
            $end = date_format($end, 'Y-m-d');
            $end = $this->dateToStr($end);
            $parent = 'id_'.$text;
            $name1 = $addeddate['person1'];
            $name2 = $addeddate['person2'];
            if($startweek <= $start && $start <= $endweek){
              $color = "rgb(255, 99, 132, 0.4)";
            } 
            else{
              $color = "rgb(54, 162, 235, 0.4)";
            }
            $jstring = array(
              "id" => $id,
              "text" => $pname,
              "start_date" => $start,
              "end_date" => $end,
              "parent" => $parent,
              "name_1" => $name1,
              "name_2" => $name2,
              "color" => $color
            );
            $jstring = json_encode($jstring);
            array_push($json, $jstring);

            //adds subfields for the miscellaneous tasks
            $subkeys = array_keys($addeddate);
            $miscsubcount = 0;
            //loops through each subcategory to the current miscellaneaous project and adds that info to the $json variable 
            foreach($addeddate as $task){
              $taskname = strval($subkeys[$miscsubcount]);
              if($taskname != "person1" && $taskname != "person2" && $taskname != "due"){
                $tid = 'id_'.$taskname.'_'.$pname.'_'.$project['projectname'];
                $taskstart = $task['due'];
                $taskstart = $this->dateToStr($taskstart);
                //If the task doesn't have a due date or is in the past, it will skip this iteration of the loop
                if($taskstart == "None" || $taskstart < $today){
                  $miscsubcount++;
                  continue;
                }
                $taskend = new \DateTime($this->dateToStr($taskstart));
                $taskend = $taskend->add(new DateInterval('P1D'));
                $taskend = date_format($taskend, 'Y-m-d');
                $taskend = $this->dateToStr($taskend);
                $taskname1 = $task['person1'];
                if($startweek <= $taskstart && $taskstart <= $endweek){
                  $color = "rgb(255, 99, 132, 0.4)";
                } 
                else{
                  $color = "rgb(54, 162, 235, 0.4)";
                }
                  $taskname2 = $task['person2'];
                  $jstring = array(
                    "id" => $tid,
                    "text" => $taskname,
                    "start_date" => $taskstart,
                    "end_date" => $taskend,
                    "parent" => $id,
                    "name_1" => $taskname1,
                    "name_2" => $taskname2,
                    "color" => $color
                  );
                $jstring = json_encode($jstring);
                array_push($json, $jstring);
              }
              $miscsubcount++;
            }
            $addedcount++;  
            $j++;      
          }
        break; 
        }
        
        //Sets all other fields as a JSON format
        $pname = $keys[$i];
        $id = 'id_'.$i.$project['projectname'];
        $start = $duedate['due'];
        $start = $this->dateToStr($start);
        //If the task doesn't have a due date or is in the past, it will skip this iteration of the loop
        if($start == "None" || $start < $today){
          $i++;
          continue;
        }
        $end = new \DateTime($this->dateToStr($start));
        $end = $end->add(new DateInterval('P1D'));
        $end = date_format($end, 'Y-m-d');
        $end = $this->dateToStr($end);
        $parent = 'id_'.$text;
        $name1 = $duedate['person1'];
        if($startweek <= $start && $start <= $endweek){
          $color = "rgb(255, 99, 132, 0.4)";
        } 
        else{
          $color = "rgb(54, 162, 235, 0.4)";
        }
        if (isset($duedate['person2'])){
          $name2 = $duedate['person2'];
          $jstring = array(
            "id" => $id,
            "text" => $pname,
            "start_date" => $start,
            "end_date" => $end,
            "parent" => $parent,
            "name_1" => $name1,
            "name_2" => $name2,
            "color" => $color
          );
        }
        else{
        $jstring = array(
          "id" => $id,
          "text" => $pname,
          "start_date" => $start,
          "end_date" => $end,
          "parent" => $parent,
          "name_1" => $name1,
          "color" => $color
        );
        }
        $jstring = json_encode($jstring);
        array_push($json, $jstring);

        //After adding the individual project, it now goes through and converts the subcategories within that project into JSON format
        $subkeys = array_keys($duedate);
        $subcount = 0;
        foreach($duedate as $task){
          $taskname = strval($subkeys[$subcount]);
          if($taskname != "person1" && $taskname != "person2" && $taskname != "due"){
            $tid = 'id_'.$taskname.'_'.$pname.'_'.$project['projectname'];
            $taskstart = $task['due'];
            $taskstart = $this->dateToStr($taskstart);
            //If the task doesn't have a due date or is in the past, it will skip this iteration of the loop
            if($taskstart == "None" || $taskstart < $today){
              $subcount++;
              continue;
            }
            $taskend = new \DateTime($this->dateToStr($taskstart));
            $taskend = $taskend->add(new DateInterval('P1D'));
            $taskend = date_format($taskend, 'Y-m-d');
            $taskend = $this->dateToStr($taskend);
            $taskname1 = $task['person1'];
            if($startweek <= $taskstart && $taskstart <= $endweek){
              $color = "rgb(255, 99, 132, 0.4)";
            } 
            else{
              $color = "rgb(54, 162, 235, 0.4)";
            }
            if (isset($task['person2'])){
              $taskname2 = $task['person2'];
              $jstring = array(
                "id" => $tid,
                "text" => $taskname,
                "start_date" => $taskstart,
                "end_date" => $taskend,
                "parent" => $id,
                "name_1" => $taskname1,
                "name_2" => $taskname2,
                "color" => $color
              );
            }
            else{
              $jstring = array(
                "id" => $tid,
                "text" => $taskname,
                "start_date" => $taskstart,
                "end_date" => $taskend,
                "parent" => $id,
                "name_1" => $taskname1,
                "color" => $color
              );
            }
            $jstring = json_encode($jstring);
            array_push($json, $jstring);
          }
          $subcount++;
        }
        $i++;
      }
      return $json;
    }
  }
  

/**************** End of the Project Planner or Sticky Note Application *********************/

  /**
   * Finds a project in the database by $id and deletes it from the database.
   * @param $id
   * @return redirect /projectindex
   */
  public function destroy($id) {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

