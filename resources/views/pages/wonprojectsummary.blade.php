@extends('layouts.default')
@section('content')

<div class="container">
  <h2><b>Project Search</b></h2> 
  <br />
  <!-- Search Bar Form -->
  <div class="active-pink-3 active-pink-4 mb-4">
    <form class="form-inline md-form mr-auto mb-4" method="post" action="{{action('ProjectController@search')}}"> 
      @csrf 
      <input name="search" class="form-control mr-sm-2" type="text" placeholder="Search Projects" aria-label="Search">
      <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Search</button>
    </form> 
  </div>

  <br />
  @if (\Session::has('success'))
  <div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
  </div><br />
  @endif
  <h2><b>Project Index</b></h2> 
  <table class="table table-striped">
    <!-- this is the table header / titles for the columns -->
    <thead>
      <tr>
        <th colspan="2">Action</th>
        <th>Project Name</th>
        <th>Dollar Value</th>
        <th colspan="2">Date NTP</th>
        <th colspan="2">Date Energization</th>
        @foreach($th_headerMonthBins as $each)  
          <th>{{ $each }}</th>
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
        @foreach($project['averagePERmonth'] as $each)
          <td>{{ $each }}</td>
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
        <td>Total</td>
        @foreach($total_footer_array as $each)
          <td>{{ $each }}</td>
        @endforeach 
      </tr>
      </tbody>
  </table>
</div>
@stop

























