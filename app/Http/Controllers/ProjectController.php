<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Project;
use App\Charts\HoursChart;

class ProjectController extends Controller
{
  protected function store($project, $req)
  {
    $project->cegproposalauthor= $req->get('cegproposalauthor');
    $project->projectname= $req->get('projectname');
    $project->clientcontactname= $req->get('clientcontactname');
    $project->clientcompany = $req->get('clientcompany');
    $project->mwsize = $req->get('mwsize');
    $project->voltage = $req->get('voltage');
    $project->dollarvalueinhouse = $req->get('dollarvalueinhouse');
    $project->dateproposed = $req->get('dateproposed');
    $project->datentp = $req->get('datentp');
    $project->dateenergization = $req->get('dateenergization');
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

  public function new_project()
  {
    return view('pages.newproject');
  }

  public function create(Request $request)
  {
    $this->validate_request($request); 
    $project = new Project();
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Project has been successfully added.');
  }

  public function update(Request $request, $id)
  {
    $this->validate_request($request);   
    $project = Project::find($id);
    $this->store($project, $request);
    return redirect('/projectindex')->with('success', 'Project has been successfully updated');
  }

  public function index()
  {
    $projects=Project::all();
    return view('pages.projectindex', compact('projects'));
  }

  public function search(Request $request)
  {
    $term = $request['search'];
    $projects = Project::whereRaw(['$text' => ['$search' => $term]])->get();
    return view('pages.projectindex', compact('projects')); 
  }

  public function edit_project($id)
  {
    $project = Project::find($id);
    return view('pages.editproject', compact('project'));
  }

  public function summary()
  {
    //Steve's code goes here
    return view('pages.wonprojectsummary');
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
      return array('labels' => [0, 1, 2, 3], 'dataset' => ['No Data', 'line', [0, 1, 2, 3]]);
      } 
    }
    
    $chart = new HoursChart;

    $chart_info = get_chart_info($request['project_id']);
    $chart->labels($chart_info['labels']);
    $chart->dataset($chart_info['dataset'][0], $chart_info['dataset'][1], $chart_info['dataset'][2])->options([
      'borderColor'=>'#3cba9f', 'fill' => False]);
    return view('pages.hoursgraph', compact('projects', 'chart'));
  }

  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect('/projectindex')->with('success','Project has been deleted');
  }

}

