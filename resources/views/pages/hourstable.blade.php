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
    if(isset($project)){
        $hours_data = $project[0]['hours_data'];
        $year = array_keys($hours_data);
        print " {$year[0]}";
        $months_array = array_keys($year[0]);
        
    }
}
?>
@endsection