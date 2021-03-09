@extends('layouts.requestform')
@section('page-title', 'Dev Request Form')
@section('title', 'Dev Request Form')
@section('subtitle', 'Feature Requests, Bug Fixes, Error Reporting')

@section('request_type')
<option value="feature">Feature</option>
<option value="bug/error">Bug/Error</option>
@stop

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