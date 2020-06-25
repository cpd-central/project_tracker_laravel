@extends('layouts.index')
<style>
  th {position: sticky;
  top: 0;
  background-color:lightgray;
  }
</style>

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
      <th>Physical Drawing Package</th>
      <th>Wiring and Controls Drawing Package</th>
      <th>Collection Drawing Package</th>
      <th>Transmission Drawing Package</th>
      <th>Communication Architecture Drawing</th>
      <th>Field Work Dates</th>
      <th>Scada</th>
      <th>RTAC/Networking Configuration File</th>
      <th>Studies</th>
    </tr>
  </thead>
@stop

@section('table-content')
@foreach($projects as $project)
    <tr>
        <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}" class="btn btn-warning">Manage Project</a></td>
        <td>{{$project['projectname']}}</td>
        <td>{{$project['dateenergization']}}</td>
        @if(isset($project['duedates']))
          @if(isset($project['duedates']['physical']))
          <td>{{$project['duedates']['physical']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['control']))
          <td>{{$project['duedates']['control']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['collection']))
          <td>{{$project['duedates']['collection']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['transmission']))
          <td>{{$project['duedates']['transmission']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['communication']))
          <td>{{$project['duedates']['communication']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['fieldwork']))
          <td>{{$project['duedates']['fieldwork']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['scada']))
          <td>{{$project['duedates']['scada']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['rtac']))
          <td>{{$project['duedates']['rtac']['due']}}</td>
          @else
          <td>None</td>
          @endif
          @if(isset($project['duedates']['studies']))
          <td>{{$project['duedates']['studies']['due']}}</td>
          @else
          <td>None</td>
          @endif
        @endif
    </tr>
@endforeach
@stop