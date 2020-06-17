<!DOCTYPE html>
@extends('layouts.index')

@section('page-title', 'Account Directory')
@section('table-title', 'Account Directory')


@section('table-header')

    <table class="table table-striped">
    <thead style="text-align:center">
      <tr>
          <th>Edit</th>
          <th>User</th>
          <th>Name</th>
          <th>Nickname for Project Hours</th>
          <th>Job Class</th>
          <th>Per hour $ value</th>
          <th>Role</th>
          <th>Terminate</th>
      </tr>
    </thead>
@stop

@section('table-content')
@foreach($users as $user)
    <?php if($user['role'] == "sudo"){
        continue;
    } ?>
    <tr id="{{$user['email']}}">
    <td><a href='{{ route('pages.editaccount', $user['_id']) }}' role="button" class="btn btn-warning">Edit</a></td>
    <td>{{ $user['email'] }}</td>
    <td align="center">{{ $user['name'] }}</td>
    <td align="center">{{ $user['nickname'] }}</td>
    <td align="center">{{ $user['jobclass'] }}</td>
    <td align="center">{{ $user['perhourdollar'] }}</td>
    <td align="center">{{ $user['role'] }}</td>
    <td align="center"><a href='{{ route('pages.rolesDelete', $user['_id']) }}' role="button" class="btn btn-danger" onclick="return confirm('This will delete the user from the database.  Are you sure you want to do this?')">Delete</a></td>
        </tr>
@endforeach 
@stop