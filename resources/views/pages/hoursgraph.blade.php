<!DOCTYPE html>
@extends('layouts.default')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="card">
      <div class="card-header">Select Projects to Graph</div>
      <div class="card-body">
        <form>	
          <div class="row">
            <div class="col-md">
              <div class="form-group">	
                <label for="project-content">Select Project</label>
                <select name="project_id_1" class="form-control">
                  <option value=" ">-----Select Project Code-----</option>	
                  @foreach($projects as $project)	
                  <option value="{{ $project['_id'] }}">{{ $project['Project Code'] }}</option>
                  @endforeach	
                </select>
              </div>	
            </div>
            <div class="col-md">
              <div class="form-group">	
                <label for="project-content">Select Project</label>
                <select name="project_id_2" class="form-control">
                  <option value=" ">-----Select Project Code-----</option>	
                  @foreach($projects as $project)	
                  <option value="{{ $project['_id'] }}">{{ $project['Project Code'] }}</option>
                  @endforeach	
                </select>
              </div>	
            </div>						
            <div class="col-md">
              <div class="form-group">	
                <label for="project-content">Select Project</label>
                <select name="project_id_3" class="form-control">
                  <option value=" ">-----Select Project Code-----</option>	
                  @foreach($projects as $project)	
                  <option value="{{ $project['_id'] }}">{{ $project['Project Code'] }}</option>
                  @endforeach	
                </select>
              </div>	
            </div>
          </div>	
          <div class="row">
            <div class="form-group text-center">
              <button type="submit" class="btn btn-success">Compare Projects!</button>
            </div>
          </div>
        </form>	
      </div>
    </div>
  </div>
  <div>	
    @isset($chart1)	
    {!! $chart1->container() !!} 	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $chart1->script() !!}
    @endisset	
  </div>
  <div class="w-100 p-3">	
    @isset($chart2)	
    {!! $chart2->container() !!} 	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $chart2->script() !!}
    @endisset	
  </div>
  </body>
</div>
@endsection






