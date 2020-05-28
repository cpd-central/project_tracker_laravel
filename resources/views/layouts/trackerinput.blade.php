<!doctype html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<html>
  <title id="page-title">@yield('page-title')</title>
  <head>
    @include('includes.trackernavbar')
  </head>
  <body>
    <h2><b>@yield('title')</b></h2>
    <div class="container">
    </div>
    <form method="post">
      @csrf
      <div class="row">
        <div class="form-group col-md-4">
            <label for="project-name">Project Name</label>
        <input type="text" class="form-control" name="project-name" value="@if(old('project-name')){{ old('project-name') }} @else<?= $__env->yieldContent('project-name')?>@endif">
        </div>
        <div class="form-group col-md-4">
           <label for="dateproposed">Project Due Date</label>
        <input type="date" class="form-control" name="project-due" value="@if(old('project-due'))<?= old('project-due') ?>@else<?= $__env->yieldContent('project-due')?>@endif">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </div>

    </form>
  </body>
</html>