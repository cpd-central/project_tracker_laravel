@extends('layouts.default')
@section('content')
@section('page-title', "Won Project Summary")

</br>
</br>
<h2><b>Total Project Dollars Per Month</b></h2>
<div>
  @isset($chart) 
  {!! $chart->container() !!} 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  {!! $chart->script() !!}
  @endisset
</div>
</br>
</br>
<div class="container">
  @if (\Session::has('success'))
  <div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
  </div><br />
  @endif
  @if (count($projects) > 0)
    <h2><b>Monthly Breakdown By Project</b></h2> 
    <table class="table table-striped">
      <!-- this is the table header / titles for the columns -->
      <thead>
        <tr>
          <th></th>
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
          <th>Dollar Value</th>
          <th colspan="2">Date NTP</th>
          <th colspan="2">Date Energization</th>
          @foreach($months as $month)  
            <th>{{ $month }}</th>
          @endforeach
     </thead>

      <tbody>
      <!-- this is the table contencts for each queried project -->
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
        </tbody>
    </table>
  @else
    <h2>No Won Projects to Display</h2>
  @endif
</div>
@stop

























