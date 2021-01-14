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