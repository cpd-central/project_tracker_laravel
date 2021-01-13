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
          <th>Activation</th>
      </tr>
    </thead>
@stop

@section('table-content')
@foreach($users_active as $user)
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
    @if($user['active'] == true)
    <td align="center"><a href='{{ route('pages.activation', $user['_id']) }}' role="button" class="btn btn-danger" onclick="return confirm('This will deactivate the user from the database.  Are you sure you want to do this?')">Deactivate</a></td>
    @else
    <td align="center"><a href='{{ route('pages.activation', $user['_id']) }}' role="button" class="btn btn-success">Activate</td>
    @endif
        </tr>
@endforeach 
@foreach($users_inactive as $user)
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
    @if($user['active'] == true)
    <td align="center"><a href='{{ route('pages.activation', $user['_id']) }}' role="button" class="btn btn-danger" onclick="return confirm('This will deactivate the user from the database.  Are you sure you want to do this?')">Deactivate</a></td>
    @else
    <td align="center"><a href='{{ route('pages.activation', $user['_id']) }}' role="button" class="btn btn-success">Activate</td>
    @endif
        </tr>
@endforeach 
@stop