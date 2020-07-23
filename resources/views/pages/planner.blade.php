@extends('layouts.index')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<style>
  th {position: sticky;
  top: 0;
  background-color:lightgray;
  }
</style>
@section('toptool')

@if($copy == null)
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
    <!-- Sorting Form -->
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
  @endif

@section('table-title', 'Projects to Manage')
@section('table-header')
<table class="table table-striped">
  <thead>
    <tr> 
      <th class="text-center" colspan="2">Action</th>
      <th>Project Name</th>
      <th>Date of Energization</th>
      <th>Physical Drawing Package</th>
      <th>Wiring and Controls Drawing Package</th>
      <th>Collection Drawing Package</th>
      <th>Transmission Drawing Package</th>
      <th>Scada</th>
      <th>Studies</th>
    </tr>
  </thead>
@stop

@section('table-content')
@foreach($projects as $project)
    <tr>
          <!-- If the copy button wasn't pressed. show the Manage Project and Copy buttons under each project -->
          @if($copy == null)
            <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}" class="btn btn-warning">Manage Project</a></td>
            <td>
            <form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.planner') }}"> 
                @csrf 
                <button class="btn btn-success" type="submit">Copy</button>
                <input type="hidden" id="copyproject" name="copyproject" value="{{$project['_id']}}" readonly />
            </form> 
            </td>
          <!-- If the copy button was pressed. show the Paste button under every project that isn't the project being copied -->
          @else
            @if($project['_id'] != $copy['_id'])
            <td>
            <form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.planner') }}"> 
              @csrf 
              <button class="btn btn-success" id="paste" type="submit">Paste</button>
              <input type="hidden" id="copyproject" name="copyproject" value="{{$copy['_id']}}" readonly />
              <input type="hidden" id="pasteproject" name="pasteproject" value="{{$project['_id']}}" readonly />
            </form>
            </td>
            <td></td>
            @else
            <td><a href="{{action('ProjectController@planner')}}" class="btn btn-warning">Cancel Copy</a></td>
            <td></td>
            @endif
          @endif
        <td>{{$project['projectname']}}</td>
        <td>{{$project['dateenergization']}}</td>
        <!-- if there are due dates saved for the current project in the loop, then it will go through each category and if those categories exist, it will display the due date for it -->
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
          @if(isset($project['duedates']['scada']))
          <td>{{$project['duedates']['scada']['due']}}</td>
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