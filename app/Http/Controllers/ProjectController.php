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
      $projects = Project::whereRaw(['$text' => ['$regex' => "{$term}"]])->get();
      //$projects = Project::whereRaw('$text', 'regexp', "/$term/")->get();

      //$projects = Project::whereRaw(['$text' => ['$search' => "{$term}"]])->get();
      //$projects = Project::where('text', 'regexp', "/$term/")->get();
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
    $projects = Project::whereRaw(['$and' => array(['projectcode' => ['$ne' => null]], ['hours_data' => ['$exists' => 'true']])])->get()->sortBy('projectname');

    function get_chart_info($id)
    {
      $selected_project = Project::where('_id', $id)->first();
      $selected_project_name = $selected_project['projectname'];
      $selected_project_id = $selected_project['_id'];
      if ($selected_project)
      {
        $hours_data = $selected_project['hours_data'];

        $years = array_keys($hours_data);
        $hours_arr = array();
        $labels_arr = array();

        foreach($years as $year)
        {
          $year_hours_data = $hours_data[$year];
          $months = array_keys($year_hours_data);
          foreach($months as $month)
          {
            array_push($labels_arr, $month . '-' . $year);

            $people_hours = $year_hours_data[$month];

            $total_project_hours = $people_hours['Total'];
            array_push($hours_arr, $total_project_hours);
          }
        }
      //We only want the data from the first non zero entry to the lst non zero entry in the set 
      //array_filter will remove all zero entries
      //we take the start key and end key of the zeros removed array
      //we use these keys to get the slice of the original array between those keys 
      $hours_array_filtered = array_filter($hours_arr);
      $start_key = key($hours_array_filtered);
      //moves pointer to end
      end($hours_array_filtered);
      $end_key = key($hours_array_filtered);

      $hours_arr_start_end = array_slice($hours_arr, $start_key, $end_key - $start_key + 1);
      $labels_arr_start_end = array_slice($labels_arr, $start_key, $end_key - $start_key + 1);
      $labels = $labels_arr_start_end;
      $dataset = array($selected_project['code'] . ' Hours', 'line', $hours_arr_start_end); 
      return array('labels' => $labels, 'dataset' => $dataset, 'title' => "{$selected_project['projectname']}  - Past Hours"); 
      }
      else
      {
      return Null;
      }
    }

    $chart_info = get_chart_info($request['project_id']);
    if (isset($chart_info))
    {
      $chart = new HoursChart;
      $chart->title($chart_info['title']);
      $chart->labels($chart_info['labels']);
      $chart->dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2])->options([
        'borderColor'=>'#3cba9f', 'fill' => False]);
      return view('pages.hoursgraph', compact('projects', 'chart'));
    }
    else
    {
      return view('pages.hoursgraph', compact('projects'));
    }
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

