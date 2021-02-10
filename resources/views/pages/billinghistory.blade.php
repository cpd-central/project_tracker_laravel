@extends('layouts.default')
@section('page-title', 'Billing History')
@section('content')
<div class="form-group col-md-4">
<form class="form-inline md-form mr-auto mb-4" method="post" action='/billinghistorysearch'> 
          @csrf
          <label for="code">Project Code For Billing History:</label>
          <input type="text" class="form-control" name="code" style="text-transform:uppercase" value="@if(old('code')){{ old('code') }}@else<?= $__env->yieldContent('code')?>@endif">
          <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Submit</button>           
</form> 
</div>
<?php $has_projects = false;
if(isset($code)){ ?>
    <h1 style="text-align: center;">{{$code}}</h1>
<?php  if(isset($project)){
        $has_projects = true; ?>
        <table class="table table-striped">
          <div id='divhead'>
            <thead id='header'>
              <tr>
                <th>Date</th>
                <th>Billed</th>
              </tr>
            </thead>
          </div>
          <?php 
            $year_keys = array_keys($project['bill_amount']);
            foreach($year_keys as $year){
                $month_keys = array_keys($project['bill_amount'][$year]);
                foreach($month_keys as $month){
                    ?>
                    <tr>
                    <td>{{$month}} {{$year}}</td>
                    <td>{{$project['bill_amount'][$year][$month]}}</td>
                    </tr>
        <?php   }
            }
          ?>

  <?php }
    if($has_projects != true){ ?>
        <h4 style="text-align: center;">No projects associated with the provided Code.</h4>
    <?php } 
} ?>
@endsection