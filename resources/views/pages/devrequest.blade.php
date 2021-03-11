@extends('layouts.requestform')
@section('page-title', 'Dev Request Form')
@section('title', 'Dev Request Form')
@section('subtitle', 'Feature Requests, Bug Fixes, Error Reporting')

@section('request_type')
<option value="Feature">Feature</option>
<option value="Bug/Error">Bug/Error</option>
@stop

@section('priority')
<option value="1 - Low">Low - backlog</option>
<option value="2 - Medium">Medium - look at when convenient</option>
<option value="3 - High">High - needs attention</option>
@stop

@section('status', 'Open')
@section('subject', '')
@section('proposer', auth()->user()->name)
@section('date', now())
@section('body', '')

@section('image')
<div class="form-group col-md-4">
    <label for="image">Image upload(jpeg,png,jpg):</label>
    <input type="file" name="image" class="form-control">
</div>
@stop