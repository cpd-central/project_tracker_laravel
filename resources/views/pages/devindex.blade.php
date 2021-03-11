@extends('layouts.index')

@section('toptool')
<style>
    .switch {
              position: relative;
              display: inline-block;
              width: 30px;
              height: 17px;
            }
    .switch input { 
                  opacity: 0;
                  width: 0;
                  height: 0;
                }
    .slider {
                  position: absolute;
                  cursor: pointer;
                  top: 0;
                  left: 0;
                  right: 0;
                  bottom: 0;
                  background-color: #ccc;
                  -webkit-transition: .2s;
                  transition: .2;
                }

    .slider:before {
                  position: absolute;
                  content: "";
                  height: 13px;
                  width: 13px;
                  left: 2px;
                  bottom: 2px;
                  background-color: white;
                  -webkit-transition: .2s;
                  transition: .2s;
                }

                input:checked + .slider {
                  background-color: #2196F3;
                }

                input:focus + .slider {
                  box-shadow: 0 0 1px #2196F3;
                }

                input:checked + .slider:before {
                  -webkit-transform: translateX(13px);
                  -ms-transform: translateX(13px);
                  transform: translateX(13px);
                }

                /* Rounded sliders */
        .slider.round {
                  border-radius: 34px;
                }

        .slider.round:before {
                  border-radius: 50%;
                }

        #labels {
            font-family: 'Roboto Medium', sans-serif;
            font-size: 16px;
        }
</style>
<a href="{{action('HomeController@dev_request')}}" class="btn btn-success">New Request</a>
<form method="post" action="{{ action('HomeController@dev_filter') }}"> 
          @csrf 
          <label id="labels">Toggle All Requests:</label>
          <label class="switch">
            <input name ="toggle_all" id="toggle_all" type="checkbox" value="all" @if(isset($toggle) && $toggle == 'all'){{'checked'}}@endif>
            <span class="slider round"></span>
          </label>
          <br>
          <button class="btn btn-dark" type="submit">Toggle</button>           
</form> 
@stop

@section('table-title', 'Dev Index')
@section('table-header')
<table class="table table-striped">
    <div id='divhead'>
      <thead id='header'>
      <tr> 
        <th class="text-center" colspan="2">Action</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Type</th>
        <th>Subject</th>
        <th>CEG Proposal Author</th>
        <th>Date</th>
      </tr>
    </thead>
  </div>
@stop

@section('table-content')
    @foreach($reqs as $request)
    <tr>
      <td><a href="{{ url('/devrequest', $request['id'] )}}" class="btn btn-warning">View</a></td>
      <td>
        <form action="{{ url('/devdelete', $request['id']) }}" method="post">
          @csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit" onclick="return confirm('This will delete the project from the database.  Are you sure you want to do this?')">Delete</button>
        </form>
      </td>
      <td>{{$request['status']}}</td>
      <td>{{$request['priority']}}</td>
      <td>{{$request['type']}}</td>
      <td>{{$request['subject']}}</td>
      <td>{{$request['proposer']}}</td>
      <td>{{$request['date']}}</td>
    </tr>
    @endforeach
@stop
