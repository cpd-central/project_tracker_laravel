@extends('layouts.default')

@section('content')

<div>
  @isset($chart) 
  {!! $chart->container() !!} 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  {!! $chart->script() !!}
  @endisset
</div>

<div class="container">
  @if (\Session::has('success'))
  <div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
  </div><br />
  @endif
  <h2><b>Monthly Breakdown</b></h2> 
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
          <th>{{ $each }}</th>
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
        <td>{{ $project['dollarvalueinhouse'] }}</td>
        <td colspan="2">{{ $project['datentp'] }}</td>
        <td colspan="2">{{ $project['dateenergization'] }}</td>
        @foreach($project['per_month_dollars'] as $per_month)
          <td> {{ $per_month }} </td>
        @endforeach
      </tr>
      @endforeach
      <!-- this is the table footer which is just going to be the totals of the columns -->
      <tr>
        <td></td>
        <td></td>
        <td></td>        
        <td></td>
        <td></td>
        <td></td>
       	<td></td>


      </tr>
      </tbody>
  </table>
</div>
@stop

























