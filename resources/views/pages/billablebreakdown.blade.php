<!DOCTYPE html>
<?php
$months_array = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>
@extends('layouts.default')
@section('page-title', 'Billable Breakdown')
@section('content')
<br>
<h2><b>Senior</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "senior"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>

<h2><b>Project</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "project"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>

<h2><b>SCADA</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "SCADA"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>

<h2><b>Drafting</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "drafting"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>

<h2><b>Testing</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "testing"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>

<h2><b>Interns-Admin</b></h2> 
<table class="table table-striped">
<th style="background-color:lightblue;">
    <?php foreach($months_array as $m){ ?>
    <td style="background-color:lightblue;"><b>{{$m}}</b></td>
    <?php }?>
    <td style="background-color:lightblue;"><b>Average</b></td>
</th>
<?php 
foreach($users_array as $u){ 
    if($u['jobclass'] == "interns-admin"){?>
<tr>
    <td>{{$u['nickname']}}</td>
    <?php 
    for($x = 0; $x < 13; $x++){ ?>
    <td> {{$emp_hours_array[$u['nickname']][$x]}}%</td>
    <?php
    } ?>
</tr>
<?php }
} ?>
</table>


@endsection