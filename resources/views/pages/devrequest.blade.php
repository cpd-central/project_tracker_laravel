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
