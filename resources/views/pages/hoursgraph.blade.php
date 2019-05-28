<!DOCTYPE html>
@extends('layouts.default')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="card">
      <div class="card-body">
        <form>	
          <div class="row">
            <div class="col-md">
              <div class="form-group">	
                <label for="project-content">Select Project</label>
                <select name="project_id" class="form-control">
                  <option value=" ">-----Select Project Code-----</option>	
                  @foreach($projects as $project)	
                  <option value="{{ $project['_id'] }}">{{ $project['projectcode'] }}</option>
                  @endforeach	
                </select>
              </div>	
            </div>	  
            <button type="submit" class="btn btn-success">Graph</button>
          </div>
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






