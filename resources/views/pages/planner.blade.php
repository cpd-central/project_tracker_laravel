@extends('layouts.index')

@section('toptool')

<div class="row"> 
  <td><a href="{{action('ProjectController@sticky_note')}}" class="btn btn-success">Sticky Note Gantt Chart</a></td>
</div>

<div class="container">
  <h2><b>Project Search</b></h2> 
  <br />
  <!-- Search Bar Form -->
  <div class="active-pink-3 active-pink-4 mb-4">
      <form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.planner') }}"> 
          @csrf 
          <input name="search" class="form-control mr-sm-2" type="text" placeholder="Search Projects" aria-label="Search" value='@if(isset($search)){{$search}}@endif'>
          <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Submit</button> 
        </form> 
  </div>
  @stop
  
  @section('sort')
  <form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.planner') }}"> 
    @csrf  
    <div class="form-group col-md-12">
      <input id="invert" name="invert" class="form-control" type="checkbox" @if(isset($invert))checked @endif value="flip"/>Flip Sort Order 
    </div> 
    <select id="sort" name='sort' class="form-control">
    <option @if(!isset($term))selected @endif>Closest Due Date</option>
    <option @if(isset($term) && $term == "projectname")selected @endif value="projectname">Project Name</option>
    <option @if(isset($term) && $term == "dateenergization")selected @endif value="dateenergization">Date Energize</option>
    <option @if(isset($term) && $term == "duedates")selected @endif value= "duedates">Due Dates</option>
  </form> 
  @stop

@section('table-title', 'Projects to Manage')
@section('table-header')
<table class="table table-striped">
  <thead>
    <tr> 
      <th>Action</th>
      <th>Project Name</th>
      <th>Date of Energization</th>
      <th>Physical Drawing Package 90%</th>
      <th>Physical Drawing Package IFC</th>
      <th>Wiring Drawing Package 90%</th>
      <th>Wiring Drawing Package IFC</th>
      <th>Collection Drawing Package 90%</th>
      <th>Collection Drawing Package IFC</th>
      <th>Transmission Drawing Package 90%</th>
      <th>Transmission Drawing Package IFC</th>
      <th>Scada</th>
      <th>Reactive Study</th>
      <th>Ampacity Study</th>
      <th>Arc Flash Study</th>
      <th>Relay and Coordination Study</th>
      <th>All Others Study</th>
    </tr>
  </thead>
@stop

@section('table-content')
@foreach($projects as $project)
    <tr>
        <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}" class="btn btn-warning">Manage Project</a></td>
        <td>{{$project['projectname']}}</td>
        <td>{{$project['dateenergization']}}</td>
        <td>{{$project['duedates']['physical90']['due']}}</td>
        <td>{{$project['duedates']['physicalifc']['due']}}</td>
        <td>{{$project['duedates']['wiring90']['due']}}</td>
        <td>{{$project['duedates']['wiringifc']['due']}}</td>
        <td>{{$project['duedates']['collection90']['due']}}</td>
        <td>{{$project['duedates']['collectionifc']['due']}}</td>
        <td>{{$project['duedates']['transmission90']['due']}}</td>
        <td>{{$project['duedates']['transmissionifc']['due']}}</td>
        <td>{{$project['duedates']['scada']['due']}}</td>
        <td>{{$project['duedates']['reactive']['due']}}</td>
        <td>{{$project['duedates']['ampacity']['due']}}</td>
        <td>{{$project['duedates']['arcflash']['due']}}</td>
        <td>{{$project['duedates']['relay']['due']}}</td>
        <td>{{$project['duedates']['allothers']['due']}}</td>
    </tr>
@endforeach
@stop