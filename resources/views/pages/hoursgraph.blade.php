<!DOCTYPE html>
@extends('layouts.default')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="card">
      <div class="card-body">
        <form method="get" id="projectcode-form">	
                <label for="project-content">Select Project</label>
                <select name="project_id" class="form-control" onchange="document.getElementById('projectcode-form').submit()">
                  <option value="0">-----Select Project Code-----</option>	
                  @foreach($projects as $project)	
                  <option value="{{ $project['_id'] }}">{{ $project['code'] }}</option>
                  @endforeach	
                </select>
        </form>	
      </div>
    </div> 
  </div>
</div>
<div>	
  @isset($chart)	
  {!! $chart->container() !!}	
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  {!! $chart->script() !!}
  @endisset	
</div>
@endsection






