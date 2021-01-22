<!DOCTYPE html>
@extends('layouts.default')
@section('page-title', 'Hours By Project Table')
@section('content')
<div class="form-group col-md-4">
<form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.hourstable') }}"> 
          @csrf 
          <label for="projectcode">Project Code:</label>
          <input type="text" class="form-control" name="projectcode" style="text-transform:uppercase" value="@if(old('projectcode')){{ old('projectcode') }}@else<?= $__env->yieldContent('projectcode')?>@endif">
          <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Submit</button>           
</form> 
</div>
<?php $has_projects = false;
if(isset($code)){ ?>
    <h1 style="text-align: center;">{{$code}}</h1>
<?php  if(isset($projects)){
        foreach($projects as $project){
            $has_projects = true;
            $hours_data = $project['hours_data'];
            $years_array = array_keys($hours_data);
            ?><h2 style="text-align: center;">{{$project['projectname']}}</h2> <?php
            foreach($years_array as $year){
                $months_array = array_keys($hours_data[$year]);
                $employee_array = array_keys($hours_data[$year][$months_array[0]]);
                unset($employee_array[count($employee_array) - 1]); //removes "Total" employee
                if($year == 2016 || $year == 2017){
                    unset($employee_array[count($employee_array) - 1]); //unsets employee "Next"
                }
                $total_employee = [count($employee_array)];
                $rates_for_total = [count($employee_array)];
                for($j = 0; $j < count($employee_array); $j++){ 
                    $total_employee[$j] = 0;
                }
                for($i = 0; $i < count($months_array); $i++){
                    for($j = 0; $j < count($employee_array); $j++){ 
                        $total_employee[$j] += $hours_data[$year][$months_array[$i]][$employee_array[$j]];
                    }
                }
                /////////////////////////////////////////////////////unset offsets the $j
                $count_employees = count($employee_array);
                for($j = 0; $j < $count_employees; $j++){ 
                    if($total_employee[$j] == 0){
                        unset($employee_array[$j]);
                        unset($total_employee[$j]);
                        unset($rates_for_total[$j]);
                    }
                }
                /////////////////////////////////////////////////////user array_keys to account for skipping indexes of $j
                $j_array = array_keys($total_employee);
                foreach($j_array as $j){
                    if($employee_array[$j] == "noname"){
                        $rates_for_total[$j] = 160;
                    }
                    foreach($users as $user){ 
                        if($user['nickname'] == $employee_array[$j]){
                            $rates_for_total[$j] = $user['hour_rates'][$year];
                        }
                    }
                }
                if(count($employee_array) == 0){
                    continue;
                }
?>
<div>
    <h4 style="text-align: center;">{{$year}}</h4>
<table class="table table-striped">
<th>
    <?php foreach($employee_array as $j){ ?>
    <td><b>{{$j}}</b></td>
    <?php }?>
</th>
<tr>
    <td><b>Rates:</b></td>
    <?php 
    $j_array = array_keys($rates_for_total);
    foreach($j_array as $j){ ?>
    <td>${{$rates_for_total[$j]}}</td>
    <?php }?>
</tr>
<?php for($i = 0; $i < count($months_array); $i++){ ?>
<tr>
    <td>{{$months_array[$i]}}</td>
    <?php foreach($employee_array as $j){ 
            if($j == "noname"){ //employee "noname" is from the Hours By Project 2017 file and has hours coded to it. 
            $calculation = $hours_data[$year][$months_array[$i]][$j] * 160;
           ?> <td>${{$calculation}}</td>
            <?php }
            foreach($users as $user){     
                if($user['nickname'] == $j){
                    $calculation = $hours_data[$year][$months_array[$i]][$j] * $user['hour_rates'][$year]; 
                ?>
    <td>${{$calculation}}</td>
    <?php }}}?>
</tr>

<?php } ?>
<tr>
    <td><b>Total</b></td>
    <?php 
    $j_array = array_keys($total_employee);
    foreach($j_array as $j){ ?>
    <td><b>${{$total_employee[$j]*$rates_for_total[$j]}}</b></td>
    <?php }?>
</tr>
</table>
</div>
<?php }
}}
    if($has_projects != true){ ?>
        <h4 style="text-align: center;">No projects associated with the provided Code.</h4>
    <?php } 
} ?>
@endsection