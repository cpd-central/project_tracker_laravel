<?php
$year = date("Y");
$month = date('F', strtotime('-21 day'));
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
        <form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.monthendbilling') }}"> 
          @csrf  
          <select id="sort" name='sort' class="form-control" onchange="this.form.submit()">
            <option @if(isset($term) && $term == "projectname") selected @endif value="projectname">A-Z Project Name</option>
            <option @if(isset($term) && $term == "projectmanager") selected @endif value="projectmanager">A-Z Project Manager</option>
          </select>
          </form>
        <table class="table table-striped">
          <div id='divhead'>
            <thead id='header'>
              <tr> 
                <th>Project Name</th>
                <th>Project Code</th>
                <th>HOLD or BILL this month?</th>
                <th>Project Manager</th>
                <th>T&M/Lump/SOV</th>
              </tr>
            </thead>
          </div>
        @foreach ($projects as $project)
          <tr>
            <td>{{$project['projectname']}}</td>
            <td>{{$project['projectcode']}}</td>
            <td><?php if(isset($project['bill_amount'][$year])){
              $month_keys = array_keys($project['bill_amount'][$year]);
              foreach($month_keys as $m){
                if($m == $month){
                  echo $project['bill_amount'][$year][$month];
                }
              }
            } ?></td>  
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
            <td>@if(isset($project['billingmethod']))
                  @if($project['billingmethod'] == 'TandM')
                    <?php echo 'T&M' ?>
                  @else
                    <?php echo $project['billingmethod'] ?>
                  @endif
              @endif </td>
          </tr>
        @endforeach
      </div>
      </div>
    </div>
  </body>
</html> 
