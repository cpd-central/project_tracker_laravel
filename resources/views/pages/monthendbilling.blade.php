<?php
$year = date("Y");
$month = date("F");
?>
<style>
  #header {
  background-color:lightblue;
  }
</style>
<!doctype html>
<html>
  <head>
    @include('includes.navbar')
  </head>
  <body>
    <div class="container">
      <div id="main">
      </br>
      </br>
      <div class="container">
        <table class="table table-striped">
          <div id='divhead'>
            <thead id='header'>
              <tr> 
                <th>Project Name</th>
                <th>Project Code</th>
                <th>Bill Amount</th>
                <th>Project Manager</th>
                <th>T&M/Hold/Lump/SOV</th>
              </tr>
            </thead>
          </div>
        @foreach ($projects as $project)
          <tr>
            <td>{{$project['projectname']}}</td>
            <td>{{$project['projectcode']}}</td>
            <td>I need to figure out what's the best way to find the last month billed stored, then check to see if it is the current month.</td>  
            <td>@if(!empty($project['projectmanager']))
                  <?php $i = 1?>
                  @foreach ($project['projectmanager'] as $manager)
                    @if($i == count($project['projectmanager']))
                      {{$manager}}
                    @else
                      {{$manager . ", "}}
                    @endcan
                  @endforeach
                @endif
            </td>
            <td>{{isset($project['bill_amount'])}}</td>
          </tr>
        @endforeach
      </div>
      </div>
    </div>
  </body>
</html> 
