<!DOCTYPE html>
<?php
$months_array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>
@extends('layouts.default')
@section('page-title', 'Billable Breakdown')
@section('content')
<table class="table table-striped">
<th>
    <?php foreach($months_array as $m){ ?>
    <td><b>{{$m}}</b></td>
    <?php }?>
    <td><b>Average</b></td>
</th>
<?php 
$i = 0;
foreach($users_array as $u){ ?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 12; $x++){ ?>
    <td> {{$emp_hours_array[$i][$x]}}</td>
    <?php
    } ?>
</tr>
<?php $i++;
} ?>


@endsection