<!DOCTYPE html>
@extends('layouts.index')

@section('page-title', 'Edit Roles')
@section('table-title', 'Edit Roles')
@section('page-title', 'Edit Roles')


@section('table-header')

    <table class="table table-striped">
    <thead style="text-align:center">
      <tr>
          <th>User</th>
          <th>Name</th>
          <th colspan="3">Role</th>
          <th>Terminate</th>
      </tr>
    </thead>
@stop

@section('table-content')
<form action="{{route('pages.rolesUpdate')}}" method="post">
    @csrf
@foreach($users as $user)
    <?php if($user['role'] == "sudo"){
        continue;
    } ?>
    <tr id="{{$user['email']}}">
    <td>{{ $user['email'] }}</td>
    <td align="center">{{ $user['name'] }}</td>
    <td align="center">User <input type="radio" name="{{$user['email']}}" value="user" @if(isset($user['role']) && $user['role'] == 'user'){{'checked=checked'}}@endif/>
    <td align="center">Proposer <input type="radio" name="{{$user['email']}}" value="proposer" @if(isset($user['role']) && $user['role'] == 'proposer'){{'checked=checked'}}@endif/>
    <td align="center">Admin <input type="radio" name="{{$user['email']}}" value="admin" @if(isset($user['role']) && $user['role'] == 'admin'){{'checked=checked'}}@endif/>
    {{-- <td><form action="{{action('HomeController@destroy', $user['id'])}}" method="post">
            @csrf
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit" onclick="return confirm('This will delete the user from the database.  Are you sure you want to do this?')">Delete</button> --}}
    <td align="center"><a href='{{ route('pages.rolesDelete', $user['_id']) }}' role="button" class="btn btn-danger" onclick="return confirm('This will delete the user from the database.  Are you sure you want to do this?')">Delete</a></td>
        </tr>
@endforeach 
{{-- </form> --}}
            <button class="btn btn-success" type="submit">Update</button>
        </form>
@stop