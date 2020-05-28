@extends('layouts.index')

@section('table-content')
@foreach($projects as $project)
    <tr>
        <td><a href="{{action('ProjectController@manage_project', $project['_id'])}}"</a></td>
        <td>{{$project['projectname']}}</td>
    </tr>
@endforeach