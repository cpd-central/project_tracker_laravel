<!DOCTYPE html>
@extends('layouts.index')

@section('page-title', 'Logs')
@section('table-title', 'Page Logs')


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
@foreach($logs as $log)
<?php $timestamps = array_keys($log); 
    foreach($timestamps as $ts){ 
    $date = new DateTime();
    $date->setTimestamp($ts);?>
    <tr>
        <td align="center"></td>
        <td align="center">{{$log[$ts]}}</td>
        <td align="center">{{date_format($date, 'Y-m-d H:i:s')}}</td>
    </tr>
    <?php }?>
@endforeach 

@stop