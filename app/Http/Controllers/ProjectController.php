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
  
  protected function store($project, $req)
  {
    $project->cegproposalauthor= $req->get('cegproposalauthor');
    $project->projectname= $req->get('projectname');
    $project->clientcontactname= $req->get('clientcontactname');
    $project->clientcompany = $req->get('clientcompany');
    $project->mwsize = $this->intCheck($req->get('mwsize'));
    $project->voltage = $this->intCheck($req->get('voltage'));
    $project->dollarvalueinhouse = $this->intCheck($req->get('dollarvalueinhouse'));
    $project->dateproposed = $this->strToDate($req->get('dateproposed'));
    $project->datentp = $this->strToDate($req->get('datentp'));
    $project->dateenergization = $this->strToDate($req->get('dateenergization'));
    $project->projecttype = $req->get('projecttype_checklist');
    $project->epctype = $req->get('epctype_checklist');
    $project->projectstatus = $req->get('projectstatus');
    $project->projectcode = $req->get('projectcode');
    $project->projectmanager = $req->get('projectmanager');
    $project->save();
  }

  protected function validate_request($req)
  {
    $this->validate($req, [
      'cegproposalauthor' => 'required',
      'projectname' => 'required',
      'clientcontactname' => 'required'
    ]);
  }

  protected function strToDate($date_string)
  {
    if (isset($date_string))
    {
      $php_date = new \DateTime($date_string, new \DateTimeZone('America/Chicago'));
      //note this is a mongodb UTCDateTime 
      $date = new UTCDateTime($php_date->getTimestamp() * 1000);
    }
    else {
      $date = "None";
    }
    return $date;
  }

  protected function dateToStr($mongo_date)
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

  protected function intCheck($integer)
  {
    if($integer == null || $integer == ""){
        $integer = -1;
    }
      return ((int)$integer);
  }

  protected function intDisplay($integer)
  {
    if($integer == -1)
    {
      $integer = "";
    }
    return $integer;
  }

  public function new_project()
  {
    return view('pages.newproject');
  }


  public function create(Request $request)
  {
    $this->validate_request($request);
    $project = new Project();
    $this->store($project, $request);
    return redirect('/projectindex')->with('Success!', 'Project has been successfully added.');
  }

  public function update(Request $request, $id)
  {
    $this->validate_request($request);
    $project = Project::find($id);
    $this->store($project, $request);
    return redirect('/projectindex')->with('Success!', 'Project has been successfully updated');
  }

  public function index()
  {
    $projects=Project::all();
    foreach($projects as $project)
    {
      $project['mwsize'] = $this->intDisplay($project['mwsize']);
      $project['voltage'] = $this->intDisplay($project['voltage']);
      $project['dollarvalueinhouse'] = $this->intDisplay($project['dollarvalueinhouse']);

      $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
      $project['datentp'] = $this->dateToStr($project['datentp']);
      $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    } 
    return view('pages.projectindex', compact('projects'));
  }

  protected function get_project_start_end($proj)
  {
    //convert the mongo UTC datetime that comes out of the database to a php datetime 
    $start = $proj['datentp']->toDateTime();
    $end = $proj['dateenergization']->toDateTime();
    return ['start' => $start, 'end' => $end];
  }

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
  
  public function indexwon()
  {
    $projects=Project::all()->where('projectstatus','Won');
    //1. Get Max end date in order to establish the # of columns needed for the table
    //Also, get smallest start date to establish the beginning of the array 
    $start_dates = array();
    $end_dates = array();
    foreach($projects as $project)
    {
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
      $per_month_dollars = $project_dollars / $num_months;
      $project_per_month_dollars = array();
      foreach($project_months as $month)
      {
        $project_per_month_dollars[$month] = $per_month_dollars;
      }
      $project['per_month_dollars'] = $project_per_month_dollars;
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

    //now loop through the projects again and update the array to have the months we are displaying, and fill with zeros for the rest
    $total_dollars = array();
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
      $total_dollars = $total_dollars + $project['per_month_dollars'];  
      foreach ($months as $month)
      {
        $total_dollars[$month] = round($total_dollars[$month] + $project['per_month_dollars'][$month], 0);
      }
      //need these three lines for proper date formatting 
      $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
      $project['datentp'] = $this->dateToStr($project['datentp']);
      $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    }
    //format total dollars with commas
    foreach($months as $month)
    {
      $total_dollars[$month] = number_format($total_dollars[$month], 0, '.', ',');
    } 
    return view('pages.wonprojectsummary', compact('months', 'projects', 'total_dollars')); 
  }



  public function search(Request $request)
  {
    $term = $request['search'];
    if (isset($term)) { 
      $projects = Project::whereRaw(['$text' => ['$search' => $term]])->get();
      foreach ($projects as $project) {
        $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
        $project['datentp'] = $this->dateToStr($project['datentp']);
        $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
      }
      return view('pages.projectindex', compact('projects')); 
    }
    else {
      return redirect('/projectindex')->with('Please enter a search term to search.');
    }
  }

  public function edit_project($id)
  {
    $project = Project::find($id);
    $project['mwsize'] = $this->intDisplay($project['mwsize']);
    $project['voltage'] = $this->intDisplay($project['voltage']);
    $project['dollarvalueinhouse'] = $this->intDisplay($project['dollarvalueinhouse']);
    $project['dateproposed'] = $this->dateToStr($project['dateproposed']);
    $project['datentp'] = $this->dateToStr($project['datentp']);
    $project['dateenergization'] = $this->dateToStr($project['dateenergization']);
    return view('pages.editproject', compact('project'));
  }


  public function hours_graph(Request $request)
  {
    $projects = DB::collection('hours_by_project')->get()->sortBy('code');
    #$projects = Project::whereRaw(['hours_data' => ['$exists' => 'true']])->get();
    
    function get_chart_info($id)
    {
      $selected_project = DB::collection('hours_by_project')->where('_id', $id)->first();

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
      return array('labels' => $labels, 'dataset' => $dataset); 
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

  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

