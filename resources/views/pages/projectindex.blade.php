@extends('layouts.index')
@section('toptool')
<div class="container">
  <!-- Search Bar Form -->
  <div style="float: left; width: 50%" class="active-pink-3 active-pink-4">
  <p style="padding-bottom:50px"></p>
  <form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.projectindex') }}"> 
    @csrf  
      <input id="invert" name="invert" class="form-control" type="checkbox" @if(isset($invert))checked @endif value="flip"/>Flip Sort Order 
      <p style="padding-right:250px"></p>
    <select id="sort" name='sort' class="form-control">
    <option @if(!isset($term))selected @endif>-----Select-----</option>
    <option @if(isset($term) && $term == "cegproposalauthor")selected @endif value="cegproposalauthor">CEG Proposal Author</option>
    <option @if(isset($term) && $term == "projectname")selected @endif value="projectname">Project Name</option>
    <option @if(isset($term) && $term == "clientcontactname")selected @endif value="clientcontactname">Client Contact</option>
    <option @if(isset($term) && $term == "clientcompany")selected @endif value="clientcompany">Client Company</option>
    <option @if(isset($term) && $term == "state")selected @endif value="state">State</option>
    <option @if(isset($term) && $term == "utility")selected @endif value="utility">Utility</option>
    <option @if(isset($term) && $term == "mwsize")selected @endif value="mwsize">MW Size</option>
    <option @if(isset($term) && $term == "voltage")selected @endif value="voltage">Voltage</option>
    <option @if(isset($term) && $term == "dollarvalueinhouse")selected @endif value="dollarvalueinhouse">CEG In-house Budget</option>
    <option @if(isset($term) && $term == "datentp")selected @endif value="datentp">Date NTP</option>
    <option @if(isset($term) && $term == "dateenergization")selected @endif value="dateenergization">Date Energize</option>
    <option @if(isset($term) && $term == "projectstatus")selected @endif value="projectstatus">Project Status</option>
    <option @if(isset($term) && $term == "projectcode")selected @endif value="projectcode">Project Code</option>
    <option @if(isset($term) && $term == "projectmanager")selected @endif value="projectmanager">Project Manager(s)</option>
  </form>
      <form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.projectindex') }}"> 
          @csrf 
          <input name="search" class="form-control mr-sm-2" type="text" placeholder="Search Projects" aria-label="Search" value='@if(isset($search)){{$search}}@endif'>
          <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Submit</button>           
  </form> 
  </div>     
  <div class="my-custom-scrollbar table-wrapper-scroll-y ">
  <h2><b>Missing Projects:</b></h2> 
  <table class="table table-bordered table-striped mb-0">
    <thead>
      <tr>
        <th scope="col">Code</th>
        <th scope="col">Project Name</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($missing_projects))
      @foreach(array_keys($missing_projects) as $mp)
      <tr>
        <td>{{$mp}}</td>
        <td>{{$missing_projects[$mp]}}</td>
      </tr>
      @endforeach
      @endif
      </tbody>
    </table>
  </div>   
  @stop

  @section('table-title', 'Project Index')
  @section('table-header')
  <table class="table table-striped">
    <div id='divhead'>
      <thead id='header'>
      <tr> 
        <th class="text-center" colspan="3">Action</th>
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
  </div>
  @stop

  @section('table-content')
    @foreach($projects as $project)
    <tr>
      <td><a href="{{action('ProjectController@copy_project', $project['_id'])}}" class="btn btn-success">Copy</a></td>
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
















