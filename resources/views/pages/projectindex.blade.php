@extends('layouts.index')
@section('toptool')
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
  @stop

  @section('table-title', 'Project Index')
  @section('table-header')
  <table class="table table-striped">
    <thead>
      <tr> 
        <th colspan="2">Action</th>
        <th>CEG Proposal Author</th>
        <th>Project Name</th>
        <th>Client Contact</th>
        <th>Client Company</th>
        <th>State</th>
        <th>Utility</th>
        <th>MW</th>
        <th>Voltage</th> 
        <th>CEG In-house Budget</th>
        <th>Date NTP</th>
        <th>Date Energize</th>   
        <th>Project Status</th>   
        <th>Project Code</th>
        <th>Project Manager</th>
      </tr>
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
      <td>{{ $project['cegproposalauthor'] }}</td>
      <td>{{ $project['projectname']}}</td >
      <td>{{ $project['clientcontactname'] }}</td>
      <td>{{ $project['clientcompany'] }}</td>
      <td>{{ $project['state'] }}</td>
      <td>{{ $project['utility'] }}</td>
      <td>{{ $project['mwsize'] }}</td>
      <td>{{ $project['voltage'] }}</td>  
      @if (is_string($project['dollarvalueinhouse'])) 
        <td>{{ $project['dollarvalueinhouse'] }}</td>
      @else
        <td>{{ number_format($project['dollarvalueinhouse'], 0, '.', ',') }}</td>
      @endif
      <td>{{ $project['datentp'] }}</td>
      <td>{{ $project['dateenergization'] }}</td>
      <td>{{ $project['projectstatus']}}</td > 
      <td>{{ $project['projectcode'] }}</td>
      <td><?php $projectmanagers = "";
        if($project['projectmanager'] != null){
          if(is_array($project['projectmanager'])){
            for($i=1; $i <= count($project['projectmanager']); $i++){
              if($i == count($project['projectmanager'])){
                $projectmanagers = $projectmanagers.$project['projectmanager'][$i-1];
              }
              else{
                $projectmanagers = $projectmanagers.$project['projectmanager'][$i-1].', ';
              }
            }
          }
          else{
            $projectmanagers = $project['projectmanager'];
          }
        }
        echo $projectmanagers?></td>
    </tr>
    @endforeach 
    @stop
















