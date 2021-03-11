@extends('layouts.requestform')
@section('page-title', 'Dev Request View')
@section('title', 'Dev Request View')
@section('subtitle', 'Feature Requests, Bug Fixes, Error Reporting')

@section('request_type')
<option value="<?=$request['type']?>">{{$request['type']}}</option>
@stop

@section('priority')
<option value="<?=$request['priority']?>">{{$request['priority']}}</option>
@stop

@section('status', $request['status'])
@section('subject', $request['subject'])
@section('proposer', $request['proposer'])
@section('date', $request['date'])
@section('body', $request['body'])

@section('image')
<?php 
if($request['image'] != null){
$path = ('img/dev/').$request['image'];
?>
<img src="{{ asset($path) }}">
<div>
<br>
</div>
<?php }?>
@stop