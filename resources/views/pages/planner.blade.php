@extends('layouts.index')

@section('table-title', 'Projects to Manage')

@section('table-header')
<table class="table table-striped">
  <thead>
    <tr> 
      <th>Action</th>
      <th>Project Name</th>
    </tr>
  </thead>
@stop

@section('table-content')
@foreach($projects as $project)
    <tr>
        <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}" class="btn btn-warning">Manage Project</a></td>
        <td>{{$project['projectname']}}</td>
    </tr>
@endforeach
@stop