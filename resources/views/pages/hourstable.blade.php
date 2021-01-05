<!DOCTYPE html>
@extends('layouts.default')
@section('page-title', 'Hours By Project Table')
@section('content')
<div class="form-group col-md-4">
<form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.hourstable') }}"> 
          @csrf 
          <label for="projectcode">Project Code:</label>
          <input type="text" class="form-control" name="projectcode" value="@if(old('projectcode')){{ old('projectcode') }}@else<?= $__env->yieldContent('projectcode')?>@endif">
          <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Submit</button>           
</form> 
</div>
<?php 
if(isset($code)){
    echo $code;
    if(isset($project[0])){
        $hours_data = $project[0]['hours_data'];
        $year = array_keys($hours_data);
        print " {$year[0]}";
        $months_array = array_keys($hours_data[$year[0]]);
        $employee_array = array_keys($hours_data[$year[0]][$months_array[0]]);
        unset($employee_array[count($employee_array) - 1]); //removes "Total" employee
        $total_employee = [count($employee_array)];
        for($j = 0; $j < count($employee_array); $j++){ 
            $total_employee[$j] = 0;
        }
        for($i = 0; $i < count($months_array); $i++){
            for($j = 0; $j < count($employee_array); $j++){ 
            $total_employee[$j] += $hours_data[$year[0]][$months_array[$i]][$employee_array[$j]];
            }
        }
        /////////////////////////////////////////////////////unset offsets the $j
        $count_employees = count($employee_array);
        for($j = 0; $j < $count_employees; $j++){ 
            if($total_employee[$j] == 0){
                unset($employee_array[$j]);
                unset($total_employee[$j]);
            }
        }
?>

<table class="table table-striped">
<th>
    <?php foreach($employee_array as $j){ ?>
    <td>{{$j}}</td>
    <?php }?>
</th>
<?php for($i = 0; $i < count($months_array); $i++){ ?>
<tr>
    <td>{{$months_array[$i]}}</td>
    <?php foreach($employee_array as $j){ ?>
    <td>{{$hours_data[$year[0]][$months_array[$i]][$j]}}</td>
    <?php }?>
</tr>

<?php } ?>
<tr>
    <td>Total</td>
    <?php foreach($total_employee as $j){ ?>
    <td>{{$j}}</td>
    <?php }?>
</tr>
<?php    }

    else{
        print " has no project associated.";
    } 
} ?>
</table>
@endsection