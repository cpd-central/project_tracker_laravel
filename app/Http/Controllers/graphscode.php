<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Project;
use App\Charts\HoursChart;

class ProjectController extends Controller
{
  public function new_project(){
    return view('project.newproject');
  }
  
  
  public function save(Request $request){
    //dd($request->all());
    $project = new Project($request->all());
    $project->save();

    if($project){
      return redirect()->route('home');
    }else{
      return back();
    }	
  }
  
  
  public function index(Request $request)
  {	
    $projects = DB::collection('projects')->where('hours_data', 'exists', true)->get()->sortBy('Project Code');
    
    function get_chart_info($id)
    {
      $today = time(); 
      $selected_project = DB::collection('projects')->where('hours_data', 'exists', true)->where('_id', $id)->first();
      
      if ($selected_project){ 
       
        //echo $selected_project['code'];
        //echo '<br>';	
        $hours_data = $selected_project['hours_data'];	

        $years = array_keys($hours_data);
        $hours_arr = array();
        $labels_arr = array();
        //loop through years and months and get the total for each month of each year
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
        //we only want the data from the first non zero entry to the last non zero entry in the set 
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
        
        
        
        
        
        
       //This is the code for if we integrate old hours into the new budget stuff 
        $end_label = strtotime(end($labels)); 
        echo $end_label;
        echo '<br>'; 
        $end_date = $selected_project['End Date']->toDateTime()->getTimestamp();

        echo date("M-Y", $end_date);
        echo '<br>';
        var_dump($end_date); 
        
        $max_month = ceil(($end_date - $end_label) / 2629743);
        $new_months = array();
        for ($i=0; $i<$max_month; $i++)
        {
          $new_month = date("M-Y", $end_label + 2629743*($i+1)); 
          if ($new_month != date("M-Y", $today))
          {
            array_push($new_months, $new_month);
          }
        }

        $dataset2_arr = array();
        foreach($new_months as $month)
        {
          array_push($dataset2_arr, 100);
        } 

        $full_labels = array_merge($labels, $new_months); 
        $dataset = array($selected_project['Project Code'] . ' Hours', 'line', $hours_arr_start_end);
        $dataset2 = array($selected_project['Project Code'] . ' Hours', 'line', $dataset2_arr);
        return array('labels' => $full_labels, 'labels2' => $new_months, 'dataset' => $dataset, 'dataset2' => $dataset2);	
        }
      else {
        return array('labels' => [0, 1, 2, 3], 'dataset' => ['No Data', 'line' , [0, 1, 2, 3]]);
      }   
    }		

    $chart1 = new HoursChart;		
    //if we get to the end of the loop and $chart_check is still false, we return the page with no chart	
    $chart_check = False;	
    $colors = ['#3e95cd', '#8e5ea2', '#3cba9f'];
    $longest_labels = array();	
    for ($i=1; $i<=3; $i++)
    {
      if($request["project_id_{$i}"])
      {	
        $chart_check = True;	
        $chart_info = get_chart_info($request["project_id_{$i}"]);	
        $labels = $chart_info['labels'];
         
        if(count($labels) > count($longest_labels))
        {
          $longest_labels = $labels;
        }
        $chart1->labels($longest_labels);	
        $chart1->dataset($chart_info['dataset'][0], 'line', $chart_info['dataset'][2])->options([
          'borderColor' => $colors[$i - 1],
          'fill' => False]);	
        $chart1->dataset($chart_info['dataset2'][0], 'line', $chart_info['dataset2'][2])->options([ 
          'borderColor' => '#3cba9f',
          'fill' => False]); 
      }	
    }

    #$chart2 = new HoursChart;
    #$longest_labels = array();	
    #foreach($projects as $project)
    #{
    #  $chart_info = get_chart_info($project['_id']);	
    #  $labels = $chart_info['labels'];	
    #  if(count($labels) > count($longest_labels))
    #  {
    #    $longest_labels = $labels;
    #  }	
    #  $chart2->labels($longest_labels);	
    #  $chart2->dataset(False, 'line', $chart_info['dataset'][2])->options(['fill' => False]);	
    #}

    if($chart_check == False)
    {
      return view('project.projectindex', compact('projects', 'selected_project'));		
    }
    //$chart2->displaylegend(false);	
    //$chart2->height(800);	
    //$chart2->width(1000);	
    return view('project.projectindex', compact('projects', 'selected_project', 'chart1', 'chart2'));	
    //return view('projectindex', compact('projects', 'selected_project', 'chart1'));	

  }
}
