<?php
$year = date("Y");
$month = date('F', strtotime('-21 day'));
?>
<style>
  th {position: sticky;
  top: 0;
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
      <table>
        <tr>
        <form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.monthendbilling') }}"> 
          @csrf  
            <td>
            <select id="sort" name='sort' class="form-control" onchange="this.form.submit()">
              <option @if(isset($term) && $term == "projectmanager") selected @endif value="projectmanager">A-Z Project Manager</option>
              <option @if(isset($term) && $term == "projectname") selected @endif value="projectname">A-Z Project Name</option>
            </select>
            </form>
            </td>
            <td style="padding-left:20px; padding-top:20px">
            <label><h2><b>Billing for {{$previous_month}} {{$year_of_previous_month}}</b></h2></label>
            </td>
          </tr>
        </table> 
        <table class="table table-striped">
          <div id='divhead'>
            <thead id='header'>
              <tr> 
                <th>Edit Project</th>
                <th>Project Name</th>
                <th>Project Code</th>
                <th style="min-width: 150px">HOLD or BILL this month?</th>
                <th style="min-width: 200px">Project Manager</th>
                <th>T&M/Lump/SOV</th>
                <th style="min-width: 150px">Billing Contact</th>
                <th>Billing Contact Email</th>
                <th style="min-width: 250px">File Location of proposal</th>
                <th style="min-width: 300px">Billing Notes</th>
              </tr>
            </thead>
          </div>
        @foreach ($projects as $project)
          <tr>
            <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit</a></td>
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
            <td>{{ $project['billingcontact'] }}</td>
            <td>{{ $project['billingcontactemail'] }}</td>
            <td>{{ $project['filelocationofproposal'] }}</td>
            <td>{{ $project['billingnotes'] }}</td>
          </tr>
        @endforeach
      </div>
      </div>
    </div>
  </body>
</html> 
