@extends('layouts.index')
@section('toptool')
<h2><b>Total Project Dollars Per Month</b></h2>
<div>
  @isset($chart) 
  {!! $chart->container() !!} 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  {!! $chart->script() !!}
  @endisset
</div>
<form action="{{ route('pages.wonprojectsummary') }}" method="POST">
  @csrf
  @isset($chart_type) 
    @if ($chart_type == 'projects')
      <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="won_prob"><button class="btn btn-primary" name="button" type="submit" value="Won/Probable View">Won/Probable View</button>
    @elseif ($chart_type == 'won_prob')
      <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="projects"><button class="btn btn-primary" name="button" type="submit" value="Won/Probable View">Projects View</button>
    @else
      <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="projects"><button class="btn btn-primary" name="button" type="submit" value="Won/Probable View">Projects View</button>
    @endif
  @endisset
</form>
</br>
</br>
@stop 

@section('table-title', 'Monthly Breakdown By Project')
@section('table-header')
<table class="table table-striped">
  <thead>
    <tr> 
      @if (count($projects))
      <form action="{{ route('pages.wonprojectsummarySearch') }}" method="POST">
        @csrf
      <th colspan="2"><select name='projectstatus' class="form-control" onchange="this.form.submit()">
        @if($projectStatus == "Won")
        <option value="All">All</option>
        <option value="Won" selected>Won</option>
        <option value="Probable">Probable</option>
        @elseif($projectStatus == "Probable")
        <option value="All">All</option>
        <option value="Won">Won</option>
        <option value="Probable" selected>Probable</option>
        @else
          <option value="All" selected>All</option>
          <option value="Won">Won</option>
          <option value="Probable">Probable</option>
        @endif
      </select></form>
      </th>
      <th></th>
      <th></th>        
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>Total</th> 
      @foreach($total_dollars as $each)
        <th>{{ number_format($each, 0, '.', ',') }}</th>
      @endforeach 
    </tr>  
    <tr>
      <th colspan="2">Action</th>
      <th>Project Name</th>
      <th>Project Status</th>
      <th>Dollar Value</th>
      <th colspan="2">Date NTP</th>
      <th colspan="2">Date Energization</th>
      @foreach($months as $month)  
        <th>{{ $month }}</th>
      @endforeach
      @else
        <h2>No Won Projects to Display</h2>
      @endif
  </thead>
@stop

@section('table-content')
  @foreach($projects as $project)
    <tr>
      <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit</a></td>
      <td>
        <form action="{{action('ProjectController@destroy', $project['id'])}}" method="post">
          @csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit" onclick="return confirm('This will delete the project from the database.  Are you sure you want to do this?')">Delete</button>
        </form>
      </td>
      <td>{{ $project['projectname']}}</td >  
      <td>{{ $project['projectstatus']}}</td>      
      @if (is_string($project['dollarvalueinhouse'])) 
        <td>{{ $project['dollarvalueinhouse'] }}</td>
      @else
        <td>{{ number_format($project['dollarvalueinhouse'], 0, ',', ',') }}</td> 
      @endif 
      <td colspan="2">{{ $project['datentp'] }}</td>
      <td colspan="2">{{ $project['dateenergization'] }}</td>
      @foreach($project['per_month_dollars'] as $per_month)
        @if (is_string($per_month)) 
          <td>0</td>
        @else
          <td> {{ number_format($per_month, 0, '.', ',') }} </td>
        @endif 
      @endforeach
    </tr>
  @endforeach 
@stop



























