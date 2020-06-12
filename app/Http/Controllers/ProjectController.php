<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Timesheet;
use MongoDB\BSON\UTCDateTime; 
use App\Charts\HoursChart;

use DateInterval;
use DatePeriod;

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
  protected function validate_request($req)
  {
    $messages = array(
      'cegproposalauthor.required' => 'The CEG Proposal Author is required.',
      'projectname.required' => 'The Project Name is required.',
      'clientcontactname.required' => 'The Client Contact Name is required.',
      'dollarvalueinhouse.required' => 'The Dollar Value in-house expense is required.',
      'datentp.required' => 'The Date of Notice To Proceed is required',
      'dateenergization.required_unless' => 'The Date of Energization is required unless Date of Energization Unknown is checked.'
    );
    $this->validate($req, [
      'cegproposalauthor' => 'required',
      'projectname' => 'required',
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

  /**
   * Creates a new project to be stored in the database. Validates the 
   * $request and assigns the user that created it to the project along with the $request
   * attributes. Redirects the route to the project index page.
   * @param $req - Request variable with attributes to be assigned to $project. 
   * @return redirect /projectindex
   */
  public function create(Request $request)
  {
    $this->validate_request($request);
    $project = new Project();
    $project->created_by = auth()->user()->email;
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Success! Project has been successfully added.');
  }

  /**
   * Updates a project in the database that was edited with the new changes.
   * @param $request - Request variable with attributes to be assigned to $project.
   * @param $id - the unique id of the project to be updated.
   * @return redirect /projectindex
   */
  public function update(Request $request, $id)
  {
    $this->validate_request($request);   
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
      $term = "projectname";
    }
    $projects = Project::whereRaw(['$and' => array(['bill_amount' => ['$ne' => null]], ['bill_amount' => ['$exists' => 'true']])])->get()->sortBy($term);
    return view('pages.monthendbilling', compact('projects', 'term'));
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
    foreach($projects as $project)
    {
      $project = $this->displayFormat($project);
    } 
    return view('pages.projectindex', compact('projects', 'term', 'search', 'invert'));
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

  /**
   * Makes hours graph for all employees and employment grouping.
   * @param $request - Request variable with attributes to be assigned to $project.
   * @return array contains labels and dateset
   */
  public function hours_graph(Request $request, $drafter_page = false) 
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
    //This array is for CEG personnel, the second field has no role in the code currently 
    $employeeLIST = array( array("Vince"       ,"senior project manager"  ,170,"senior"         , "vince@ceg.mn"                  ),
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
  
                           
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////Drafter Hours Page starts here/////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //truncate the employee list if we're in the drafter hours page
    if ($drafter_page) {
      //store the emails for the drafters 
      $employee_emails = array(); 
      //just a counter
      $z = 0; 
      foreach ($employeeLIST as $emp) {
        if ($emp[3] == 'drafting') {
          if ($emp[4]) 
          $employee_emails[$emp[0]] = $emp[4];
        }
        $z++;
      }
      
      $today = app('App\Http\Controllers\TimesheetController')->getDate();
      $end_date = clone $today;
      //date range of 30 days 
      $start_date = $today->sub(new DateInterval('P31D'));
      //now, we want access to some of the functions in our Timesheetcontroller, since the collecting of the 
      //drafters' hours is very similar to what we do in the timesheet app
      $date_arr = app('App\Http\Controllers\TimesheetController')->get_dates($start_date, $end_date)[0];
      
      $current_month = date('F');
      $previous_month = date('F', strtotime('-21 days'));
      $current_year = date('Y');
      $previous_year = date('Y', strtotime('-21 days'));
      
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
          foreach($codes_arr as $code) {
            if ($code =="CEG" or $code =="CEGTRNG" or $code =="CEGMKTG" or $code =="CEGEDU") {
              continue;
            }

          if (in_array($code, array_keys($timesheet_codes))) {
            //note, we're just getting the first description.  for most drafters, I'm assuming this will be fine,
            //but note that this is a limitation at the moment. 
            //$names = array_values($timesheet_codes);
            $index = array_search($code,array_keys($timesheet_codes));
            $projectNames = array_values($timesheet_codes)[$index];
            //dd(array_values($timesheet_codes));
            //dd($projectNames);
            //$names = array_keys($projectNames);
            //dd($names);
            $i = 0;
            foreach($projectNames as $project_hours){
             //dd($project_hours);
              $names = array_keys($projectNames);
              $projectName = $names[$i];
              //dd($projectName);
              $i++;
              //$project_hours = array_values($projectName); 
              //dd($projectName);

              //this will store the time from this date range as a kvp ('date' => 'hours') 
              $project_hours_in_date_range = array();
              //dd($project_hours_in_date_range);
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
              $total = 0;
              foreach($project_hours_in_date_range as $date){
                $total = $total + $date;
              }
              if($total <= 0){
                continue;
              }
              //////
              $chart->dataset($projectName, 'bar', array_values($project_hours_in_date_range))->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'backgroundColor'=>$fill_colors[$c_color_loop], 'fill' => true, 'hidden' => false]); 
              $options = [];
              $options['scales']['xAxes'][]['stacked'] = true;
              $options['scales']['yAxes'][]['stacked'] = true;
              $options['legend']['labels']['boxWidth'] = 10;
              $options['legend']['labels']['padding'] = 6;
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
         // if($code == "CEGMISC01"){
           // $code = 
         // }
          //put the hours in as the dataset
          //$chart->dataset($code, 'bar', array_values($project_hours_in_date_range))->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'backgroundColor'=>$fill_colors[$c_color_loop], 'fill' => true, 'hidden' => false]); 
//          $options = [];
//          $options['scales']['xAxes'][]['stacked'] = true;
  //        $options['scales']['yAxes'][]['stacked'] = true;
    //      $options['legend']['labels']['boxWidth'] = 10;
      //    $options['legend']['labels']['padding'] = 6;
        //  $chart->options($options);
          
          //now, set the project hours in the date range as the value for this employee's name
//          $employee_arr[$name] = $project_hours_in_date_range;
  //        $c_color_loop++;
    //      if($c_color_loop > $color_max){
      //      $c_color_loop = 0;
        //  }
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
      return view('pages.hoursgraph', compact('charts', 'drafter_page'));     
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////Drafter Hours Page ends here///////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    

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
                $individual_project_monies[$emp_count] = $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];  //need to fix soon
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
                  $previous_month_project_monies = $previous_month_project_monies + $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                }
              }
              switch ($employeeLIST[$emp_count][3]) {
                case "senior":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[0]=$total_individual_hours[0]+0;
                    $total_individual_monies[0]=$total_individual_monies[0]+0;
                  } else {
                    $total_individual_hours[0]=$total_individual_hours[0]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[0]=$total_individual_monies[0]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                  }
                  break;
                case "project":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[1]=$total_individual_hours[1]+0;
                    $total_individual_monies[1]=$total_individual_monies[1]+0;
                  } else {
                    $total_individual_hours[1]=$total_individual_hours[1]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[1]=$total_individual_monies[1]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                  }
                  break;
                case "SCADA":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[2]=$total_individual_hours[2]+0;
                    $total_individual_monies[2]=$total_individual_monies[2]+0;
                  } else {
                    $total_individual_hours[2]=$total_individual_hours[2]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[2]=$total_individual_monies[2]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                  }
                  break;
                case "drafting":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[3]=$total_individual_hours[3]+0;
                    $total_individual_monies[3]=$total_individual_monies[3]+0;
                  } else {
                    $total_individual_hours[3]=$total_individual_hours[3]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[3]=$total_individual_monies[3]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                  }
                  break;
                case "interns-admin":
                  if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours))) {
                    $total_individual_hours[4]=$total_individual_hours[4]+0;
                    $total_individual_monies[4]=$total_individual_monies[4]+0;
                  } else {
                    $total_individual_hours[4]=$total_individual_hours[4]+$people_hours[$employeeLIST[$emp_count][0]];
                    $total_individual_monies[4]=$total_individual_monies[4]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
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
        return (array('labels' => $labels, 'dataset' => $dataset, 'title' => "{$selected_project['projectname']}, {$selected_project['projectcode']}, PM is {$selected_project['projectmanager'][0]}", 'individual_dataset' => $individual_dataset, 'individual_dataset_monies' => $individual_dataset_monies, 'project_grand_total' => $project_grand_total, 'dollarvalueinhouse' => $dollarvalueinhouse, 'dateenergization' => $dateenergization, 'group_dataset' => $group_dataset, 'group_dataset_monies' => $group_dataset_monies,'previous_month_project_hours' => $previous_month_project_hours, 'total_project_dollars' => $total_project_dollars,'previous_month_project_monies' => $previous_month_project_monies, 'total_project_monies_per_month_dataset' => $total_project_monies_per_month_dataset, 'total_project_hours_per_month_dataset' => $total_project_hours_per_month_dataset, 'id' => "{$selected_project['id']}", 'last_bill_amount' => $last_bill_amount, 'last_bill_month' => $last_bill_month, 'billing_data' => $billing_data));
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

