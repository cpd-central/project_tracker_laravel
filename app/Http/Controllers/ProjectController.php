<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Project;
use MongoDB\BSON\UTCDateTime; 
use MongoDB\BSON\Decimal128; 
use App\Charts\HoursChart;
use UTCDateTime\DateTime;
use UTCDateTime\DateTime\DateTimeZone;

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

    $project->testbill = array(array("jan"=>10000),array("feb"=>213234));
    
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
      $php_date = new \DateTime($date_string, new \DateTimeZone('America/Chicago'));
      //note this is a mongodb UTCDateTime 
      $date = new UTCDateTime($php_date->getTimestamp() * 1000);
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
    return redirect('/projectindex')->with('Success!', 'Project has been successfully added.');
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
    return redirect('/projectindex')->with('Success!', 'Project has been successfully updated');
  }


  public function update2(Request $request, $id)
  {
    echo "HEY!";

  }



  public function blah($id)
  {
    dd($id); 


    echo "HEY!";
    echo "HEY!";
    echo "HEY!";
    //$this->validate_request($request);  //I had to comment this out because it broke my function
    //$project = Project::find($id);  
    dd($request);
    echo "HEY!";
    echo "HEY!";
    echo "HEY!";



  

 
    //name = 1_id,  value:asdjfjaewiru3243488
    echo "request:" . $request;






    //if ($project['bill_amount'] exists) {
      //$bill_array = $project['bill_amount'];
    //} 
    //else {
     // $bill_array = array();
    //} 
    //$bill_array['2019']['November'] = $amount;
    //$project->bill_amount = $bill_array;
    //$project->save();**/
    return view('pages.hoursgraph');
 //   return redirect('/hoursgraph')->with('Success!', 'Project has been successfully updated');
  }









  /**
   * If the current user has a role that is not a user, all projects are retrieved to be viewed. 
   * Otherwise, the projects retrieved are the ones only the user role type user is associated with, such
   * as their name matching the cegproposalauthor, their name matching the projectmanager field, or
   * their email matching the created_by field stored in the project.
   * @return view projectindex
   */
  public function index()
  {
    $projects=Project::all();
    foreach($projects as $project)
    {
      $project = $this->displayFormat($project);
    } 
    return view('pages.projectindex', compact('projects'));
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

  // protected function longQueriesIndexWon($status){
  //   return Project::where('projectstatus',$status)->where(function($query){
  //     $query->where('cegproposalauthor', auth()->user()->name)
  //           ->orWhere('projectmanager', auth()->user()->name)
  //           ->orWhere('created_by', auth()->user()->email);
  //   });
  // }    Keep incase we re-implement role

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
    // else{
    //   if($request['projectstatus'] == 'Won'){
    //     $projects=($this->longQueriesIndexWon('Won'))->get();
    //     $projectStatus = "Won";
    //   }
    //   else if($request['projectstatus'] == 'Probable'){
    //     $projects=($this->longQueriesIndexWon('Probable'))->get();
    //     $projectStatus = "Probable";
    //   }
    //   else{
    //     $projects=Project::where(function($query) {
    //       $query->where('projectstatus','Won')->orWhere('projectstatus','Probable');
    //     })
    //     ->where(function($query2) {
    //       $query2->where('cegproposalauthor', auth()->user()->name)
    //             ->orWhere('projectmanager', auth()->user()->name)
    //             ->orWhere('created_by', auth()->user()->email);
    //     })->get();
    //     $projectStatus = "All";
    //   }                              Keep incase we re-implement user role

    if (count($projects) > 0)
    {
      //dd($request); 
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
        //formats the project data in order to display properly
        $project = $this->displayFormat($project);

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
      #$options['maintainAspectRatio'] = false;
      $chart->options($options);
      $chart->height(600);
      #$chart->width(1200);
      #dd($chart); 
      //format total dollars with commas
      //foreach($months as $month)
      //{
      //  $total_dollars[$month] = number_format($total_dollars[$month], 0, '.', ',');
      //} 

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
  public function search(Request $request)
  {
    $term = $request['search'];
    if (isset($term)) {
      //$projects = Project::whereRaw(['$text' => ['$search' => $term]])->get();
      $projects = Project::whereRaw(['$text' => ['$search' => "{$term}"]])->get();
      //if(auth()->user()->role != 'user'){ 
        foreach ($projects as $project) {
          $project = $this->displayFormat($project);
        }
      //}
      // else{
      //   $user_email = auth()->user()->email;
      //   $user_name = auth()->user()->name;
      //   foreach ($projects as $key => $project) {
      //     if($project['created_by'] == $user_email || $project['cegproposalauthor'] == $user_name) {
      //       $project = $this->displayFormat($project);
      //     }
      //     elseif(isset($project['projectmanager'])){
      //       if(is_array($project['projectmanager']) && in_array($user_name, $project['projectmanager'])){
      //         $project = $this->displayFormat($project);
      //       }
      //       elseif($project['projectmanager'] == $user_name){
      //         $project = $this->displayFormat($project);
      //       }
      //     }
      //     else{
      //       unset($projects[$key]);
      //     }
      //   }
      // }
      return view('pages.projectindex', compact('projects')); 
      }
    else {
      return redirect('/projectindex')->with('Please enter a search term to search.');
    }
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
  * Queries the database by project code and returns an hours graph for the project.
  * @param $request - Request variable with attributes to be assigned to $project.
  * @return array contains labels and dateset
  */
  public function hours_graph(Request $request)
  {
    
    if (!isset($request['switch_chart_button'])) 
    {
      $chart_units = 'hours';
    }
    else
    { 
      $chart_units = $request['switch_chart_button'];
    }


    if (!isset($request['switch_chart_button_2'])) 
    {
      $chart_ind_vs_group = 'individuals';
    }
    else
    { 
      $chart_ind_vs_grou = $request['switch_chart_button_2'];
    }


    #get project list
    #global $project_grand_total;
    #$project_grand_total_count = 0;
    $project_grand_total = 0;
    #need to get the "current project list" from hours that have been billed
    $projectsBILLED = array("15TH AVE SUB","23 MN Interconnections","Akuo Sterling NM","Big Blue Support");
    $employeeLIST = array(array("Vince","senior project manager",170,"senior"),array("Max","senior engineer",160,"senior"),array("Pete","senior project manager",160,"senior"),array("Darko","project engineer - a", 120,"senior"),array("Rob","senior project engineer",130,"senior"), array("","senior project engineer",130,"senior"), array("Shafqat","project engineer b",110,"project"), array("Nick","SCADA Engineer",125,"SCADA"),array("Erin","project engineer - a",120,"project"),array("Yang","project engineer - a",120,"project"), array("Stephen K.","project engineer - b",110,"project"),array("Naga","project engineer - c", 95,"project"),array("Corey","project engineer - c",95,"SCADA"),array("Abdi","project engineer - b",110,"project"),array("Jacob R.","project engineer - c",95,"project"),array("Brian","SCADA Engineers",125,"SCADA"),array("Tom U.","Project Managers",125,"project"),array("Jake M.","project engineer - c",125,"SCADA"),array("Donna","admin",85,"interns-admin"),array("Kathy","electrical designer",105,"drafting"),array("Julie","cad technician drafter",85,"drafting"),array("Marilee","cad technician drafter",85,"drafting"),array("Joe M.","cad technician drafter",85,"drafting"),array("Bob B.","cad technician drafter",85,"drafting"),array("Sumitra","intern",60,"interns-admin"),array("Randy","intern",60,"interns-admin"),array("Josh","intern",60,"interns-admin"),array("Tim","intern",60,"interns-admin"));
    $groupLIST = array("senior","project","SCADA","drafter","interns-admin","blank");

    $choosen_line_colors = array('#396AB1','#DA7C30','#3E9651','#CC2529','#535154','#6B4C9A','#922428','#948B3D','#488f31','#58508d','#bc5090','ff6361','#ffa600','#7BEEA5','#127135','#008080','#1AE6E6');
    $c_color_loop = 0;

    $group_colors = array('#4d2f14','#8D5524','#C68642','#F1C27D','#ffe9cc');
    $c_group_colors = 0;

    #location dictates the group:  senior (group 0), project (group 1), drafting (group 2), interns-admin (group 3), SCADA (group 4)

    #$employeeGROUP = array("Vince","Max","Pete","Darko","Rob","Steve P.","Shafqat","Nick","Erin","Yang","Stephen K.","Naga","Corey","Abdi","Jacob R.","Brian","Tom U.","Jake M.","Donna","Kathy","Julie","Marilee","Joe M.","Bob B.","Sumitra","Randy","Josh","Tim");

    #echo "Array Test: " . $employeeLIST[0][0] . "<br>";

    #$a=array("red","green","blue");
    #echo "<br>";
    #echo "Search red/green/blue: " . array_search("blue",$a) . "<br>";
    #echo "Search employeeLIST: " . array_search("Vince",$employeeLIST) . "<br>";
    #echo "Search employeeLIST: " . $employeeLIST[array_search("Tim", array_column($employeeLIST, 0))][0] . "<br>";
    #echo "Search employeeLIST: " . $employeeLIST[array_search("Tim", array_column($employeeLIST, 0))][1] . "<br>";
    #echo "Search employeeLIST: " . $employeeLIST[array_search("Tim", array_column($employeeLIST, 0))][2] . "<br>";
    #echo "Search employeeLIST: " . $employeeLIST[array_search("Tim", array_column($employeeLIST, 0))][3] . "<br>";
    #echo "Search employeeLIST: " . array_search("60", array_column($employeeLIST, 2)) . "<br>";
    #echo "<br>";
    $projects = Project::whereRaw(['$and' => array(['projectcode' => ['$ne' => null]], ['hours_data' => ['$exists' => 'true']])])->get()->sortBy('projectname');

    #$projects = Project::whereRaw(['$and' => array(['projectcode' => ['$ne' => null]], ['hours_data' => ['$exists' => 'true']],   [    ['var' => lessthan]  ]    )])->get()->sortBy('projectname');
     function prepare_array($senior_arr, $group_count)
    {

      $senior_array_filtered[$group_count] = array_filter($senior_arr[$group_count]);
      $senior_start_key[$group_count] = key($senior_array_filtered[$group_count]);
      //moves pointer to end
      end($senior_array_filtered[$group_count]);
      $senior_end_key[$group_count] = key($senior_array_filtered[$group_count]);
      
      $senior_arr_start_end[$group_count] = array_slice($varpassed, $senior_start_key[$group_count], $senior_end_key[$group_count] - $senior_start_key[$group_count] + 1);
      //echo "<br>senior_arr_start_end: " . print_r($senior_arr_start_end[$group_count]) . "<br>";
      return $senior_arr_start_end[$group_count];

    } 


    function get_chart_info($projectnamevar)
    {
      $employeeLIST = array(array("Vince","senior project manager",170,"senior"),array("Max","senior engineer",160,"senior"),array("Pete","senior project manager",160,"senior"),array("Darko","project engineer - a", 120,"senior"),array("Rob","senior project engineer",130,"senior"), array("Steve P.","senior project engineer",130,"senior"), array("Shafqat","project engineer b",110,"project"), array("Nick","SCADA Engineer",125,"SCADA"),array("Erin","project engineer - a",120,"project"),array("Yang","project engineer - a",120,"project"), array("Stephen K.","project engineer - b",110,"project"),array("Naga","project engineer - c", 95,"project"),array("Corey","project engineer - c",95,"SCADA"),array("Abdi","project engineer - b",110,"project"),array("Jacob R.","project engineer - c",95,"project"),array("Brian","SCADA Engineers",125,"SCADA"),array("Tom U.","Project Managers",125,"project"),array("Jake M.","project engineer - c",95,"SCADA"),array("Donna","admin",85,"interns-admin"),array("Kathy","electrical designer",105,"drafting"),array("Julie","cad technician drafter",85,"drafting"),array("Marilee","cad technician drafter",85,"drafting"),array("Joe M.","cad technician drafter",85,"drafting"),array("Bob B.","cad technician drafter",85,"drafting"),array("Sumitra","intern",60,"interns-admin"),array("Randy","intern",60,"interns-admin"),array("Josh","intern",60,"interns-admin"),array("Tim","intern",60,"interns-admin"));
      $groupLIST = array("senior","project","SCADA","drafter","interns-admin");
      #$id = "ObjectId('5cf6a2fbb047910e4cf196c9')";
      #$id = '5cf6a310b047910e4cf19b4f';
      #select project  
      $selected_project = Project::where('projectname', $projectnamevar)->first();
      //echo "selected_project: " . "<pre>" . print_r($selected_project) . "<pre>" . "endselectedproject<br>";
      $selected_project_name = $selected_project['projectname'];
      #echo "projectname: " . $selected_project_name . "<br>";
      $selected_project_id = $selected_project['_id'];
      if ($selected_project)
      {
        #get all hours data for project


        
        $hours_data = $selected_project['hours_data'];
        $years = array_keys($hours_data);
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
        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++)
        {
          $individual_project_hours_arr[$emp_count] = array();
          $individual_project_monies_arr[$emp_count] = array();
        }
        foreach($years as $year)
        {
          $year_hours_data = $hours_data[$year];
          $months = array_keys($year_hours_data);
          foreach($months as $month)
          {
            array_push($labels_arr, $month . '-' . $year);

            $people_hours = $year_hours_data[$month];
            /*foreach($people_hours as $person)
            {
              $person - this is the hours for each person
              looping through all people in the month
            }*/

            //echo "month: " . $month . "<br>";
            //echo "month2: " . $previous_month . "<br>";


            $total_project_hours = $people_hours['Total'];
            array_push($hours_arr, $total_project_hours);

            #echo print_r($employeeLIST);
            #echo "employeeLIST: " . $employeeLIST[0][0] . "<br>";
            #echo "count employliest" . count($employeeLIST) . "<br>";
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

            for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++)
            {
              #echo "employeeLIST emp count 0: " . $employeeLIST[$emp_count][0] . "<br>"; 
              #echo "people_hours: " . print_r($people_hours) . "<br>"; 
              #,$people_hours))
              #$test_array=array("Vince","Bob");
              if (!in_array($employeeLIST[$emp_count][0],array_keys($people_hours)))
                {
                  #echo "I found: " . $employeeLIST[$emp_count][0] . "<br>";
                  continue;
                }
              

              $individual_project_hours[$emp_count] = $people_hours[$employeeLIST[$emp_count][0]];  //need to fix soon
             #echo "ROW 726  individual_project_hours[emp_count]" . $emp_count . " : " . $individual_project_hours[$emp_count] . "<br>";
              array_push($individual_project_hours_arr[$emp_count], $individual_project_hours[$emp_count]);  
            
              $individual_project_monies[$emp_count] = $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];  //need to fix soon
             #echo "ROW 726  individual_project_hours[emp_count]" . $emp_count . " : " . $individual_project_hours[$emp_count] . "<br>";
              array_push($individual_project_monies_arr[$emp_count], $individual_project_monies[$emp_count]);  

              if ($month == $previous_month AND $current_year==$year)
                {
                  $previous_month_project_hours = $previous_month_project_hours + $people_hours[$employeeLIST[$emp_count][0]];
                  //echo "previous_month_project_hours: " . $previous_month_project_hours . "<br>";
                }

              if ($month == $previous_month AND $current_year==$year)
                {
                  $previous_month_project_monies = $previous_month_project_monies + $people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                  //echo "previous_month_project_hours: " . $previous_month_project_monies. "<br>";
                }

              //if ($people_hours[$employeeLIST[$emp_count][0]]>0)
              //{
                switch ($employeeLIST[$emp_count][3]) {
                    case "senior":
                        $total_individual_hours[0]=$total_individual_hours[0]+$people_hours[$employeeLIST[$emp_count][0]];
                        $total_individual_monies[0]=$total_individual_monies[0]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                        #echo "<br>senior_total_individual " . $selected_project_name . $month ." :" . 
                        #echo "<pre>" . print_r($senior_arr) . "<pre>";
                        break;
                    case "project":
                        $total_individual_hours[1]=$total_individual_hours[1]+$people_hours[$employeeLIST[$emp_count][0]];
                        $total_individual_monies[1]=$total_individual_monies[1]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                        break;
                    case "SCADA":
                        $total_individual_hours[2]=$total_individual_hours[2]+$people_hours[$employeeLIST[$emp_count][0]];
                        $total_individual_monies[2]=$total_individual_monies[2]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                        break;
                    case "drafting":
                        $total_individual_hours[3]=$total_individual_hours[3]+$people_hours[$employeeLIST[$emp_count][0]];
                        $total_individual_monies[3]=$total_individual_monies[3]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                        //echo "<br>group_arr: " . print_r($group_project_hours_arr[3]) . "<br>";
                        //$group_project_hours_arr[3]=$total_individual_hours[3];
                       #echo "<br>total_individual: " . $selected_project_name . " " . $month . ": " . print_r($total_individual_hours[3]) . "<br>";
                        break;
                    case "interns-admin":
                        $total_individual_hours[4]=$total_individual_hours[4]+$people_hours[$employeeLIST[$emp_count][0]];
                        $total_individual_monies[4]=$total_individual_monies[4]+$people_hours[$employeeLIST[$emp_count][0]]*$employeeLIST[$emp_count][2];
                        break;
                    default:
                        break;



                //};
                    };
              



              #echo "individual emp_count: " . $emp_count . " <br>";
              #echo print_r($individual_project_hours_arr[$emp_count]) . "<br><br><br><br>";
            }
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

            #echo "<br>group_arr: " . print_r($group_project_hours_arr[3]) . "<br>";
            #echo "Total Vince: " . $vince_arr . "<br>";
            #array_push($vince_arr, $vince_project_hours);
          }
        }
        $total_project_dollars = array_sum($group_project_monies_arr[0]) + array_sum($group_project_monies_arr[1]) + array_sum($group_project_monies_arr[2]) + array_sum($group_project_monies_arr[3]) + array_sum($group_project_monies_arr[4]);
      //echo "total_project_dollars" . $total_project_dollars . "<br>";

      #echo "ROW 738  this spot individual_dataset: <br>";
      #echo print_r($individual_project_hours_arr) . "<br><br><br><br>";
     



      //We only want the data from the first non zero entry to the lst non zero entry in the set 
      //array_filter will remove all zero entries
      //we take the start key and end key of the zeros removed array
      //we use these keys to get the slice of the original array between those keys 
      $project_grand_total  =  (array_sum($hours_arr));
      $dollarvalueinhouse = $selected_project['dollarvalueinhouse'];
      $dateenergization = $selected_project['dateenergization'];
      #echo "dollarvalueinhouse: " . $dollarvalueinhouse . "<br>";

      //MIGHT WANT TO ADD IN FUTURE
      //for ($group_count=0; $group_count<count($groupLIST); $group_count++)
      //{
      //$tempvar[$group_count] = prepare_array($senior_arr[$group_count], $group_count);
      //$finaldataset[$group_count]  = array('Senior Hours', 'line', $tempvar[$group_count]); 
      //}




      //$senior_array_filtered = array_filter($senior_arr);
      //$senior_start_key = key($senior_array_filtered);
      //moves pointer to end
      //end($senior_array_filtered);
      //$senior_end_key = key($senior_array_filtered);
      
      //$senior_arr_start_end = array_slice($senior_arr, $senior_start_key, $senior_end_key - $senior_start_key + 1);
      #echo "<br>senior_arr_start_end: " . print_r($senior_arr_start_end) . "<br>";
      //$senior_dataset = array('Senior Hours', 'line', $senior_arr_start_end); 

      #echo "<br>senior_dataset: " . print_r($senior_dataset) . "<br>";



      $hours_array_filtered = array_filter($hours_arr);
      $start_key = key($hours_array_filtered);
      //moves pointer to end
      end($hours_array_filtered);
      $end_key = key($hours_array_filtered);

      $hours_arr_start_end = array_slice($hours_arr, $start_key, $end_key - $start_key + 1);
      $labels_arr_start_end = array_slice($labels_arr, $start_key, $end_key - $start_key + 1);


      $labels = $labels_arr_start_end;
      $dataset = array(' Total Hours', 'line', $hours_arr_start_end);      
      //echo "<br>Project: " . $selected_project_name . "";
      #echo "<br>1a hour_arr: <br>";
      #echo print_r($hours_arr) . "<br>";
      //echo "<br>1a dataset: <br>";
      //echo print_r($dataset) . "<br>";
      //echo "<br>1a labels: <br>";
      //echo print_r($labels) . "<br>";

      //doin gthis for each individual employee's hours
      for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++)
      {
        #$individual_array_filtered[$emp_count] = array_filter($individual_project_hours_arr[$emp_count]);
        #$individual_start_key[$emp_count] = key($individual_array_filtered[$emp_count]);
        //moves pointer to end
        #end($individual_array_filtered[$emp_count]);
        #$individual_end_key[$emp_count] = key($individual_array_filtered[$emp_count]);
        
        #$individual_project_hours_arr_start_end[$emp_count] = array_slice($individual_project_hours_arr[$emp_count], $individual_start_key[$emp_count], $individual_end_key[$emp_count] - $individual_start_key[$emp_count] + 1);
        $individual_project_hours_arr_start_end[$emp_count] = array_slice($individual_project_hours_arr[$emp_count], -count($labels),count($labels));
        $individual_dataset[$emp_count] = array($employeeLIST[$emp_count][0] . ' Hours', 'line', $individual_project_hours_arr_start_end[$emp_count]); 

        $individual_project_monies_arr_start_end[$emp_count] = array_slice($individual_project_monies_arr[$emp_count], -count($labels),count($labels));
        $individual_dataset_monies[$emp_count] = array($employeeLIST[$emp_count][0] . ' Dollars', 'line', $individual_project_monies_arr_start_end[$emp_count]);

      }

      //echo "<br>1b individual_arr[0]: <br>";
      //echo print_r($individual_project_hours_arr[0]) . "<br>";
      //echo "<br>1b individual_dataset[0]: <br>";
      //echo print_r($individual_dataset[0]) . "<br>";


      for ($group_count=0; $group_count<count($groupLIST); $group_count++)
      {
        #$group_array_filtered[$group_count] = array_filter($group_project_hours_arr[$group_count]);
        #$group_start_key[$group_count] = key($group_array_filtered[$group_count]);
        //moves pointer to end
        #end($group_array_filtered[$group_count]);
        #$group_end_key[$group_count] = key($group_array_filtered[$group_count]);
        
        #$group_project_hours_arr_start_end[$group_count] = array_slice($group_project_hours_arr[$group_count], $group_start_key[$group_count], $group_end_key[$group_count] - $group_start_key[$group_count] + 1);
        $group_project_hours_arr_start_end[$group_count] = array_slice($group_project_hours_arr[$group_count], -count($labels),count($labels));
        $group_dataset[$group_count] = array($groupLIST[$group_count] . ' Hours', 'line', $group_project_hours_arr_start_end[$group_count]); 

        $group_project_monies_arr_start_end[$group_count] = array_slice($group_project_monies_arr[$group_count], -count($labels),count($labels));
        $group_dataset_monies[$group_count] = array($groupLIST[$group_count] . ' Dollars', 'line', $group_project_monies_arr_start_end[$group_count]); 



      }
      



      $total_project_monies_per_month_arr_start_end = array_slice($total_project_monies_per_month_arr, -count($labels),count($labels));
      $total_project_monies_per_month_dataset = array('Total Dollars', 'line', $total_project_monies_per_month_arr_start_end);
  
      $total_project_hours_per_month_arr_start_end = array_slice($total_project_hours_per_month_arr, -count($labels),count($labels));
      $total_project_hours_per_month_dataset = array('Total Hours', 'line', $total_project_hours_per_month_arr_start_end);


      #echo "<br>1c group_arr: <br>";
      #echo print_r($group_project_hours_arr[1]) . "<br><br><br>";
      #echo "<br>1c group_dataset[0]: <br>";
      #echo print_r($group_dataset[0]) . "<br><br><br>";
        //echo "<br>group_array_filtered: " . print_r($group_array_filtered[3]) . "<br>";
       // echo "<br>group_start_key: " . ($group_start_key[3]) . "<br>";
        //echo "<br>end group array filtered: " . print_r(end($group_array_filtered[3])) . "<br>";

        #echo "<br>group_dataset: " . print_r($group_dataset[3]) . "<br>";




      #echo "<br>individual_dataset: " . print_r($individual_dataset[0]) . "<br>";
      #echo "<br>finaldataset: " . print_r($finaldataset) . "<br><br><br>";


      #echo "senior_dataset: " . print_r($senior_dataset) . "<br><br>";
      #echo "ROW 776  individual_dataset: " . print_r($individual_dataset) . "<br><br><br><br>";

      return (array('labels' => $labels, 'dataset' => $dataset, 'title' => "{$selected_project['projectname']}  - Past Hours", 'individual_dataset' => $individual_dataset, 'individual_dataset_monies' => $individual_dataset_monies, 'project_grand_total' => $project_grand_total, 'dollarvalueinhouse' => $dollarvalueinhouse, 'dateenergization' => $dateenergization, 'group_dataset' => $group_dataset, 'group_dataset_monies' => $group_dataset_monies,'previous_month_project_hours' => $previous_month_project_hours, 'total_project_dollars' => $total_project_dollars,'previous_month_project_monies' => $previous_month_project_monies, 'total_project_monies_per_month_dataset' => $total_project_monies_per_month_dataset, 'total_project_hours_per_month_dataset' => $total_project_hours_per_month_dataset, 'id' => "{$selected_project['id']}")); 
      }
      else
      {
      return Null;
      }
    }

    $current_month = date('F');
    $previous_month = date('F', strtotime('-1 month'));
    $current_year = date('Y');
    $previous_year = date('Y', strtotime('-1 year'));
    $year_of_previous_month = date('Y', strtotime('-1 month')); 

    #echo "current_year: " . $current_year . "<br>" . "previous_month: " . $previous_month . "<br>";

    $non_zero_projects = Project::whereRaw([
      '$and' => array([
        'hours_data' => ['$exists' => 'true'],
        '$and' => array([
          "hours_data.{$current_year}.{$previous_month}.Total"=> ['$exists' => 'true'],
          "hours_data.{$current_year}.{$previous_month}.Total" =>['$ne'=>0]
        ])
      ])
      ]
    )->get()->sortByDesc("hours_data.{$current_year}.{$previous_month}.Total");

    #echo "projectname: ";
    #echo "<pre>";
    #print_r($non_zero_projects);
    #echo "<pre>" . "exit" . "<br>";


    #echo print_r($non_zero_projects);
 
    #$array_for_finding_most_time_spent = array();

    #echo "Here's the count: " . count($non_zero_projects) . "<br>";
    $i=0;
    $i_max = count($non_zero_projects) . "<br>";
    foreach($non_zero_projects as $non_zero_project)
    {
      #$chart_info = get_chart_info($non_zero_project['_id']);     
      #echo "chart_info: " . print_r($chart_info) . "<br>";
      /*$hours_data = $non_zero_project['hours_data'];
      $years = array_keys($hours_data);
      $hours_arr = array();
      $labels_arr = array();
      $year_hours_data = $hours_data[$year_of_previous_month];
      $months = array_keys($year_hours_data);
      array_push($labels_arr, $previous_month . '-' . $year_of_previous_month);
      $people_hours = $year_hours_data[$previous_month];
      */
      /*foreach($people_hours as $person)
      {
        $person - this is the hours for each person
        looping through all people in the month
      }*/
      $a = $non_zero_project['projectname'];
      if ($a =="CEG - General" or $a =="CEG Research and Training" or $a =="Education & Training" or $a =="CEG - Marketing" or $a == "NEEDS NAME")
      {
        #echo "projectname: " . $non_zero_project['projectname'] . "<br>";
        continue;

      }
      #echo "people hours: " . $people_hours['Total'] . "<br>";
      /*$total_project_hours = $people_hours['Total'];
      array_push($hours_arr, $total_project_hours);

      $vince_project_hours[1] = $people_hours['Vince'];
      array_push($vince_arr[1], $vince_project_hours[1]);
      */
      #echo "<br>" . "non_zero_projectspecial: " . $non_zero_project['projectname'] . "<br>";
      $chart_info = get_chart_info($non_zero_project['projectname']);

      $chart_variable[$i]= $chart_info['project_grand_total'];
      $dollarvalueinhousearray[$i]= $chart_info['dollarvalueinhouse'];
            #echo "chart_variable: ";
      #print_r($chart_variable[$i]);
      #echo "<br>";
      

      #$varnum->project_grand_total($chart_info['project_grand_total']);
      #$varnum= $chart_info->get($chart_info['project_grand_total']);




      #$varnum = ;
      #$varnum->project_grand_total($chart_info['project_grand_total']);
      #$project->cegproposalauthor= $req->get('cegproposalauthor');
       #$project->varnum=$chart_info->get('project_grand_total');
       #$varnum = $this->chart_info($non_zero_project['project_grand_total']);
       #echo "varnum: " . print_r($varnum) . "<br>";

      if (isset($chart_info))
      {
        $c_color_loop=0;
        $chart_hours[$i] = new HoursChart;
        $chart_hours[$i]->title($chart_info['title']);
        $chart_hours[$i]->labels($chart_info['labels']);
        #$chart[$i]->type($chart_info['scatter']);
        #$chart[$i]->setScriptAttribute($chart_info['project_grand_total']); 
        $chart_hours[$i]->dataset($chart_info['total_project_hours_per_month_dataset'][0], $chart_info['total_project_hours_per_month_dataset'][1], $chart_info['total_project_hours_per_month_dataset'][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false])->dashed([3]);

        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++)
          {
          if ($c_color_loop==count($choosen_line_colors))
            { $c_color_loop=0;}

            #echo "chart_info: " . $chart_info['individual_dataset'][$emp_count][2][0] . "<br>";

              #echo "individual_project_hours: " . $individual_project_hours[$emp_count] . "<br>";
              #echo "chart_info[individual_dataset]: " . $chart_info['individual_dataset'][$emp_count][2][0] . "<br>"; 
              if ( array_sum($chart_info['individual_dataset'][$emp_count][2]) <> 0)
                {
                  $chart_hours[$i]->dataset($chart_info['individual_dataset'][$emp_count][0], $chart_info['individual_dataset'][$emp_count][1], $chart_info['individual_dataset'][$emp_count][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false]);
                }
            
            $c_color_loop=$c_color_loop+1;  
          }  


        $c_color_loop=0;


        //echo "<br><pre>" . $chart_info['title'] . print_r($chart_info['senior_arr']) . "<pre><br>";
        //echo "<br><br>";
        //echo "<br><pre>" . print_r($chart_info['finaldataset']) . "<pre><br>";
        //for ($group_count=0; $group_count<count($groupLIST); $group_count++) //count($groupLIST)
          //echo "<br>groupLIST" . count($groupLIST) . "<br>";
        #$chart_info['group_dataset'][4][2][4]=0; 

        //echo "<br><pre>" . print_r($chart_info['group_dataset'][4][2][4]) . "<pre><br>";

        for ($group_count=0; $group_count<(count($groupLIST)-1); $group_count++) //count($groupLIST)
          {
            //echo "<br>group count" . $group_count  . "<br>";
            if ( array_sum($chart_info['group_dataset'][$group_count][2]) <> 0)
                {

            $chart_hours[$i]->dataset($chart_info['group_dataset'][$group_count][0], $chart_info['group_dataset'][$group_count][1], $chart_info['group_dataset'][$group_count][2])->options(['borderColor'=>$group_colors[$group_count], 'fill' => False, 'hidden' => true]);
                }
          } 



        $chart_hours[$i]->options([
            'dateenergization' => 
               $this->dateToStr($chart_info['dateenergization'])
        ]);

        $chart_hours[$i]->options([
            'dollarvalueinhouse' => 
               $chart_info['dollarvalueinhouse'] 
        ]);
        $chart_hours[$i]->options([
            'CEGtimespenttodate' => 
               $chart_info['project_grand_total']
        ]);
        $chart_hours[$i]->options([
            'total_project_dollars' => 
               $chart_info['total_project_dollars']
        ]);

        $chart_hours[$i]->options([
            'previous_month_project_monies' => 
               $chart_info['previous_month_project_monies']
        ]);


        $chart_hours[$i]->options([
            'previous_month_project_hours' => 
               $chart_info['previous_month_project_hours']
        ]);

        $chart_hours[$i]->options([
            'id' => 
               $chart_info['id']
        ]);

        $chart_hours[$i]->options([
            'tooltip' => [ 
               'visible' => true 
             ]
        ]);
 





        $c_color_loop=0;
        $chart_dollars[$i] = new HoursChart;
        $chart_dollars[$i]->title($chart_info['title']);
        $chart_dollars[$i]->labels($chart_info['labels']);
        #$chart[$i]->type($chart_info['scatter']);
        #$chart[$i]->setScriptAttribute($chart_info['project_grand_total']); 
        $chart_dollars[$i]->dataset($chart_info['total_project_monies_per_month_dataset'][0], $chart_info['total_project_monies_per_month_dataset'][1], $chart_info['total_project_monies_per_month_dataset'][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false])->dashed([3]);

        $c_color_loop=0;
        //this is the graph for monies
        for ($emp_count=0; $emp_count<count($employeeLIST); $emp_count++)
          {
          if ($c_color_loop==count($choosen_line_colors))
            { $c_color_loop=0;}

            #echo "chart_info: " . $chart_info['individual_dataset'][$emp_count][2][0] . "<br>";

              #echo "individual_project_hours: " . $individual_project_hours[$emp_count] . "<br>";
              #echo "chart_info[individual_dataset]: " . $chart_info['individual_dataset'][$emp_count][2][0] . "<br>"; 
              if ( array_sum($chart_info['individual_dataset_monies'][$emp_count][2]) <> 0)
                {
                  $chart_dollars[$i]->dataset($chart_info['individual_dataset_monies'][$emp_count][0], $chart_info['individual_dataset_monies'][$emp_count][1], $chart_info['individual_dataset_monies'][$emp_count][2])->options(['borderColor'=>$choosen_line_colors[$c_color_loop], 'fill' => False, 'hidden' => false]);
                }
            
            $c_color_loop=$c_color_loop+1;  
          }

        //echo "<br><pre>" . $chart_info['title'] . print_r($chart_info['senior_arr']) . "<pre><br>";
        //echo "<br><br>";
        //echo "<br><pre>" . print_r($chart_info['finaldataset']) . "<pre><br>";
        //for ($group_count=0; $group_count<count($groupLIST); $group_count++) //count($groupLIST)
          //echo "<br>groupLIST" . count($groupLIST) . "<br>";
        #$chart_info['group_dataset'][4][2][4]=0; 

        //echo "<br><pre>" . print_r($chart_info['group_dataset'][4][2][4]) . "<pre><br>";





        for ($group_count=0; $group_count<(count($groupLIST)-1); $group_count++) //count($groupLIST)
          {
            //echo "<br>group count" . $group_count  . "<br>";
            if ( array_sum($chart_info['group_dataset'][$group_count][2]) <> 0)
                {

            $chart_dollars[$i]->dataset($chart_info['group_dataset_monies'][$group_count][0], $chart_info['group_dataset_monies'][$group_count][1], $chart_info['group_dataset_monies'][$group_count][2])->options(['borderColor'=>$group_colors[$group_count], 'fill' => False, 'hidden' => true]);
              }
          }  



        $chart_dollars[$i]->options([
            'dateenergization' => 
               $this->dateToStr($chart_info['dateenergization'])
        ]);

        $chart_dollars[$i]->options([
            'dollarvalueinhouse' => 
               $chart_info['dollarvalueinhouse'] 
        ]);
        $chart_dollars[$i]->options([
            'CEGtimespenttodate' => 
               $chart_info['project_grand_total']
        ]);
        $chart_dollars[$i]->options([
            'total_project_dollars' => 
               $chart_info['total_project_dollars']
        ]);

        $chart_dollars[$i]->options([
            'previous_month_project_monies' => 
               $chart_info['previous_month_project_monies']
        ]);


        $chart_dollars[$i]->options([
            'previous_month_project_hours' => 
               $chart_info['previous_month_project_hours']
        ]);

        $chart_dollars[$i]->options([
            'id' => 
               $chart_info['id']
        ]);

        $chart_dollars[$i]->options([
            'tooltip' => [ 
               'visible' => true 
             ]
        ]);

      }




      else
      {}
      #echo "<br><pre>" . dd($chart_info) . "<pre><br>";
      #echo "<br><pre>" . print_r($chart_dollars) . "<pre><br>";
      #echo "<br>sum array: " . array_sum($chart_info['individual_dataset'][0][2]) . "";
      $i++;
      }
      #echo "<pre>";
      #print_r($chart);
      #echo "<pre>";
      #print_r($chart_variable);

      #echo "<br>";
      #echo "<br>";









 

      #if (isset($chart_info))
      #{
      #  $chart = new HoursChart;
      #  $chart->title($chart_info['title']);
      #  $chart->labels($chart_info['labels']);
      #  $chart->dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2])->options([
      #    'borderColor'=>'#3cba9f', 'fill' => False]);
      #  return view('pages.hoursgraph', compact('projects', 'chart'));
      #}
      #else
      #{
      #  return view('pages.hoursgraph', compact('projects'));
      #}




      #echo "non_zero_projects: " . $non_zero_project['projectname'] . "<br>";
      #echo "total_project_hours: " . array_sum($hours_arr) . "<br>";

      #$loopsarray = array($non_zero_project['projectname']=>array_sum($hours_arr));
      #echo "TesT:" . print_r($loopsarray);


      #$array_for_finding_most_time_spent[$non_zero_project['projectname']] = array_sum($hours_arr);


      #$array_for_finding_most_time_spent[non_zero_project['projectname']] = array_sum($hours_arr);
      #rsort($array_for_finding_most_time_spent);
      #echo "array for finding most time spent: " . print_r($array_for_finding_most_time_spent) . "<br>";
      #echo "start: ";
     # $arrlength = count($array_for_finding_most_time_spent);
      #for($x = 0; $x < $arrlength; $x++) {
      #      echo $array_for_finding_most_time_spent[$x];
       #     echo ",";
        #}
         #   echo "<br>";



    #arsort($array_for_finding_most_time_spent);
    #echo "array for finding most time spent: " . print_r($array_for_finding_most_time_spent) . "<br>"; 

    #$chart_info = get_chart_info($request['project_id']);

    #foreach($array_for_finding_most_time_spent as $var => $value)
    #{
        #echo 'key: ' . key($var) . '<br>';
        #echo '<br>';
        #echo 'Project: ' . $var . '<br>';
        #echo 'Hours: ' . $value . '<br>';
    #}
    #$chart_info = get_chart_info(array_for_finding_most_time_spent['projectname']);
    





    #$chart_info = get_chart_info($request['project_id']);







    #foreach($chart_info as $var)
    #{
    #echo $chart_info['dataset'][$var] . "<br>"; #dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2]) . "<br>";
    #echo "echoing the dataset:" . print_r($chart_info['dataset'][2]) . "<br>"; #dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2]) . "<br>";
    #};

  #$cars=array($chart,$chart2);
  #echo print_r($cars);

  #echo "chart[$var]: " . $chart[$var] . "<br>"; 
  #echo "chart[$var]: " . $var . "<br>"; 


  return view('pages.hoursgraph', compact('projects', 'chart_hours', 'chart_dollars', 'chart_variable','dollarvalueinhousearray','chart_units','id'));
  }

  /**
   * Finds a project in the database by $id and deletes it from the database.
   * @param $id
   * @return redirect /projectindex
   */
  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

