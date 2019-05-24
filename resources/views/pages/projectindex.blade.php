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
    <thead>
      <tr>
        <th colspan="3">Action</th>
        <th>CEG Proposal Author</th>
        <th>Project Name</th>
        <th>Client Contact</th>
        <th>Client Company</th>
        <th>MW</th>
        <th>CEG In-house Budget</th>
        <th>Date NTP</th>
        <th>Date Energize</th>   
        <th>Project Status</th>   
        <th>Project Code</th>
        <th>Project Manager</th>


      </tr>
    </thead>
    <tbody>

      @foreach($projects as $project)
      <tr>
        <td><a href="{{action('ProjectController@new_project', $project['_id'])}}" class="btn btn-primary">New</a></td>
        <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit</a></td>
        <td>
          <form action="{{action('ProjectController@destroy', $project['id'])}}" method="post">
            @csrf
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>

        <td>{{ $project['cegproposalauthor'] }}</td>
        <td>{{ $project['projectname']}}</td >        
        <td>{{ $project['clientcontactname'] }}</td>
        <td>{{ $project['clientcompany'] }}</td>
        <td>{{ $project['mwsize'] }}</td>
        <td>{{ $project['dollarvalueinhouse'] }}</td>
        <td>{{ $project['datentp'] }}</td>
        <td>{{ $project['dateenergization'] }}</td>
        <td>{{ $project['projectstatus']}}</td > 
        <td>{{ $project['projectcode'] }}</td>
        <td>{{ $project['projectmanager'] }}</td>


      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@stop















