@extends('layouts.index')

@section('toptool')
<a href="{{action('HomeController@dev_request')}}" class="btn btn-success">New Request</a>
@stop

@section('table-title', 'Dev Index')
@section('table-header')
<table class="table table-striped">
    <div id='divhead'>
      <thead id='header'>
      <tr> 
        <th class="text-center" colspan="2">Action</th>
        <th>CEG Proposal Author</th>
        <th>Subject</th>
        <th>Date</th>
      </tr>
    </thead>
  </div>
@stop

@section('table-content')
    @foreach($reqs as $request)
    <tr>
      <td><button></button></td>
      <td>
        <form action="{{ url('/devdelete', $request['id']) }}" method="post">
          @csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit" onclick="return confirm('This will delete the project from the database.  Are you sure you want to do this?')">Delete</button>
        </form>
      </td>
      <td>{{$request['proposer']}}</td>
      <td>{{$request['subject']}}</td>
      <td>{{$request['date']}}</td>
    </tr>
    @endforeach
@stop
