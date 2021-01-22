<!DOCTYPE html>
@extends('layouts.index')

@section('page-title', 'Logs')
@section('table-title', 'Page Logs')

@section('toptool')
  <!-- Search Bar Form -->
  <div style="float: right; width: 50%" class="active-pink-3 active-pink-4">
  <form method="post" action="{{ route('pages.logs') }}"> 
    @csrf  
    <select id="sort" name='sort' class="form-control" onchange="this.form.submit()">
    <option @if(!isset($term))selected @endif>-----Select-----</option>
    <option @if(isset($term) && $term == "accountdirectory")selected @endif value="accountdirectory">Account Directory</option>
    <option @if(isset($term) && $term == "drafterhours")selected @endif value="drafterhours">Drafter Hours</option>
    <option @if(isset($term) && $term == "hoursgraph")selected @endif value="hoursgraph">Hours Graph</option>
    <option @if(isset($term) && $term == "hourstable")selected @endif value="hourstable">Hours Table</option>
    <option @if(isset($term) && $term == "monthendbilling")selected @endif value="monthendbilling">Month End Billing</option>
    <option @if(isset($term) && $term == "planner")selected @endif value="planner">Planner</option>
    <option @if(isset($term) && $term == "projectindex")selected @endif value="projectindex">Project Index</option>
    <option @if(isset($term) && $term == "project_tracker")selected @endif value="project_tracker">Project Tracker</option>
    <option @if(isset($term) && $term == "stickynote")selected @endif value="stickynote">Sticky Note</option>
    <option @if(isset($term) && $term == "timesheet")selected @endif value="timesheet">Timesheet</option>
    <option @if(isset($term) && $term == "timesheetsentstatus")selected @endif value="timesheetsentstatus">Timesheet Sent Status</option>
    <option @if(isset($term) && $term == "wonprojectsummary")selected @endif value="wonprojectsummary">Won Project Summary</option>
    </select>
  </form>
  </div>
@stop


@section('table-header')
    <table class="table table-striped">
    <thead style="text-align:center">
      <tr>
          <th align="center">Page</th>
          <th align="center">User</th>
          <th align="center">Time</th>
      </tr>
    </thead>
@stop

@section('table-content')
<?php $all_logs = []; 
foreach($logs as $log){
    $timestamps = array_keys($log['visitors']); 
    foreach($timestamps as $ts){
        $key_string = $ts."+".$log['visitors'][$ts];
        $all_logs[$key_string] = $log['name'];
    }
}
    krsort($all_logs);
    $log_keys = array_keys($all_logs);
    foreach($log_keys as $lk){ 
        $strings = explode("+", $lk);
        $date = new DateTime();
        $date->setTimestamp($strings[0]);?>
    <tr>
        <td align="center">{{$all_logs[$lk]}}</td>
        <td align="center">{{$strings[1]}}</td>
        <td align="center">{{date_format($date, 'Y-m-d H:i:s')}}</td>
    </tr>
    <?php }?>

@stop