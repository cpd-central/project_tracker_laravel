<!DOCTYPE html>
@extends('layouts.default')
<style>
    .switch {
              position: relative;
              display: inline-block;
              width: 30px;
              height: 17px;
            }
    .switch input { 
                  opacity: 0;
                  width: 0;
                  height: 0;
                }
    .slider {
                  position: absolute;
                  cursor: pointer;
                  top: 0;
                  left: 0;
                  right: 0;
                  bottom: 0;
                  background-color: #ccc;
                  -webkit-transition: .2s;
                  transition: .2;
                }

    .slider:before {
                  position: absolute;
                  content: "";
                  height: 13px;
                  width: 13px;
                  left: 2px;
                  bottom: 2px;
                  background-color: white;
                  -webkit-transition: .2s;
                  transition: .2s;
                }

                input:checked + .slider {
                  background-color: #2196F3;
                }

                input:focus + .slider {
                  box-shadow: 0 0 1px #2196F3;
                }

                input:checked + .slider:before {
                  -webkit-transform: translateX(13px);
                  -ms-transform: translateX(13px);
                  transform: translateX(13px);
                }

                /* Rounded sliders */
        .slider.round {
                  border-radius: 34px;
                }

        .slider.round:before {
                  border-radius: 50%;
                }

        #labels {
            font-family: 'Roboto Medium', sans-serif;
            font-size: 16px;
        }
</style>
@section('page-title', 'Hours By Project Table')
@section('content')
<div class="form-group col-md-4">
<form method="post" action="{{ route('pages.hourstable') }}"> 
          @csrf 
          <label id="labels" for="projectcode" style="margin-top: 10px">Project Code:</label>
          <input type="text" class="form-control" name="code" style="text-transform:uppercase; margin-bottom: 10px;" value="@if(isset($code)){{$code}}@endif">
          <label id="labels">Toggle Hours:</label>
          <label class="switch">
            <input name ="toggle_hours" id="toggle_hours" type="checkbox" value="hours" @if(isset($chart_units) && $chart_units == 'hours'){{'checked'}}@endif>
            <span class="slider round"></span>
          </label>
          <br>
          <button class="btn btn-dark" type="submit">Submit</button>           
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
            arsort($years_array); //Sorts the $years array from latest year to earliest year
            $totals_array = array(count($years_array));
            $senior_array_total = array(count($years_array));
            $project_array_total = array(count($years_array));
            $drafting_array_total = array(count($years_array));
            $interns_admin_array_total = array(count($years_array));
            $scada_array_total = array(count($years_array));
            $testing_total = array(count($years_array));
            ?><h2 style="text-align: center;">{{$project['projectname']}}</h2> <?php
            foreach($years_array as $year){

                $senior_array_total[$year] = 0;
                $project_array_total[$year] = 0;
                $drafting_array_total[$year] = 0;
                $interns_admin_array_total[$year] = 0;
                $scada_array_total[$year] = 0;
                $testing_total[$year] = 0;

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
                    // Searches for the key that is set for a year that has 0 hours, then unsets it from the original array. See https://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
                    if (($key = array_search($year, $years_array)) !== false){
                        unset($years_array[$key]);
                    }
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
    <td><b>Grand Total</b></td>
</th>
<?php if(!isset($chart_units) || $chart_units == 'dollars'){ //if the chart units is set to dollars, then we want to display the rates for each employee.
    ?>
<tr style="background-color:lightblue;">
    <td><b>Rates:</b></td>
    <?php 
    $j_array = array_keys($rates_for_total);
    foreach($j_array as $j){ ?>
    <td>${{$rates_for_total[$j]}}</td>
    <?php }?>
    <td></td>
</tr>
<?php $grand_total = 0;
    for($i = 0; $i < count($months_array); $i++){
        $month_total = 0;
         ?>
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
                    $month_total += $calculation;
                ?>
    <td>${{$calculation}}</td>
    <?php 
                    if($user['jobclass'] == 'senior'){
                        $senior_array_total[$year] += $calculation;
                    }elseif($user['jobclass'] == 'project'){
                        $project_array_total[$year] += $calculation;
                    }elseif($user['jobclass'] == 'drafting'){
                        $drafting_array_total[$year] += $calculation;
                    }elseif($user['jobclass'] == 'interns-admin'){
                        $interns_admin_array_total[$year] += $calculation;
                    }elseif($user['jobclass'] == 'SCADA'){
                        $scada_array_total[$year] += $calculation;
                    }elseif($user['jobclass'] == 'testing'){
                        $testing_total[$year] += $calculation;
                    }
}}}?>
        <td style="background-color:lightblue;">${{$month_total}}</td>
</tr>

<?php } ?>
<tr>
    <td><b>Total</b></td>
    <?php 
    $j_array = array_keys($total_employee);
    foreach($j_array as $j){ ?>
    <td><b>${{$total_employee[$j]*$rates_for_total[$j]}}</b></td>
    <?php $grand_total += $total_employee[$j]*$rates_for_total[$j];
    } $totals_array[$year] = $grand_total;
    ?>
    <td style="background-color:lightblue;"><b>${{$grand_total}}</b></td>
</tr>
</table>
</div>

<?php } /***********************Toggle Hours******************************/
else{ //if the chart units is set to hours, it won't calculate the total pay.
    ?>
<?php $grand_total = 0;
    for($i = 0; $i < count($months_array); $i++){
        $month_total = 0;
         ?>
<tr>
    <td>{{$months_array[$i]}}</td>
    <?php foreach($employee_array as $j){ 
            if($j == "noname"){ //employee "noname" is from the Hours By Project 2017 file and has hours coded to it. 
            ?> <td>{{$hours_data[$year][$months_array[$i]][$j]}}</td>
            <?php }
            foreach($users as $user){     
                if($user['nickname'] == $j){
                    $month_total += $hours_data[$year][$months_array[$i]][$j];
                ?>
    <td>{{$hours_data[$year][$months_array[$i]][$j]}}</td>
    <?php 
                if($user['jobclass'] == 'senior'){
                    $senior_array_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }elseif($user['jobclass'] == 'project'){
                    $project_array_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }elseif($user['jobclass'] == 'drafting'){
                    $drafting_array_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }elseif($user['jobclass'] == 'interns-admin'){
                    $interns_admin_array_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }elseif($user['jobclass'] == 'SCADA'){
                    $scada_array_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }elseif($user['jobclass'] == 'testing'){
                    $testing_total[$year] += $hours_data[$year][$months_array[$i]][$j];
                }
    }}}?>
        <td style="background-color:lightblue;">{{$month_total}}</td>
</tr>
<?php } ?>
<tr>
    <td><b>Total</b></td>
    <?php 
    $j_array = array_keys($total_employee);
    foreach($j_array as $j){ ?>
    <td><b>{{$total_employee[$j]}}</b></td>
    <?php $grand_total += $total_employee[$j];
    } $totals_array[$year] = $grand_total;
    ?>
    <td style="background-color:lightblue;"><b>{{$grand_total}}</b></td>
</tr>
</table>
</div>
<?php } 
} // Project Summary Top
$grand_total_project = 0;
?>
<div><br></div>
<div>
    <h4 style="text-align: center;">{{$project['projectname']}} Project Summary</h4>
<table class="table table-striped">
<tr style="background-color:lightblue;">
    <td><b>Year</b></td>
    <?php foreach($years_array as $year){ ?>
        <td><b>{{$year}}</b></td>
    <?php }?>
    <td><b>Total</b></td>
</tr>
<?php //*******************DOLLARS*****************
if(!isset($chart_units) || $chart_units == 'dollars'){?>
<tr>
    <td>Senior</td>
    <?php $s_total = 0;  
    foreach($years_array as $year){ ?>
        <td>${{$senior_array_total[$year]}}</td>
    <?php $s_total += $senior_array_total[$year];}?>
    <td>${{$s_total}}</td>
</tr>
<tr>
    <td>Project</td>
    <?php $p_total = 0; 
    foreach($years_array as $year){ ?>
        <td>${{$project_array_total[$year]}}</td>
    <?php $p_total += $project_array_total[$year];}?>
    <td>${{$p_total}}</td>
</tr>
<tr>
    <td>Drafting</td>
    <?php $d_total = 0;
    foreach($years_array as $year){ ?>
        <td>${{$drafting_array_total[$year]}}</td>
    <?php $d_total += $drafting_array_total[$year];}?>
    <td>${{$d_total}}</td>
</tr>
<tr>
    <td>SCADA</td>
    <?php $scada_total = 0;
    foreach($years_array as $year){ ?>
        <td>${{$scada_array_total[$year]}}</td>
    <?php $scada_total += $scada_array_total[$year];}?>
    <td>${{$scada_total}}</td>
</tr>
<tr>
    <td>Testing</td>
    <?php $t_total = 0;
    foreach($years_array as $year){ ?>
        <td>${{$testing_total[$year]}}</td>
    <?php $t_total += $testing_total[$year];}?>
    <td>${{$t_total}}</td>
</tr>
<tr>
    <td>Intern-Admin</td>
    <?php $ia_total = 0;
    foreach($years_array as $year){ ?>
        <td>${{$interns_admin_array_total[$year]}}</td>
    <?php $ia_total += $interns_admin_array_total[$year];}?>
    <td>${{$ia_total}}</td>
</tr>
<?php } //*******************HOURS*****************
    else{?>
    <tr>
    <td>Senior</td>
    <?php $s_total = 0;  
    foreach($years_array as $year){ ?>
        <td>{{$senior_array_total[$year]}}</td>
    <?php $s_total += $senior_array_total[$year];}?>
    <td>{{$s_total}}</td>
</tr>
<tr>
    <td>Project</td>
    <?php $p_total = 0; 
    foreach($years_array as $year){ ?>
        <td>{{$project_array_total[$year]}}</td>
    <?php $p_total += $project_array_total[$year];}?>
    <td>{{$p_total}}</td>
</tr>
<tr>
    <td>Drafting</td>
    <?php $d_total = 0;
    foreach($years_array as $year){ ?>
        <td>{{$drafting_array_total[$year]}}</td>
    <?php $d_total += $drafting_array_total[$year];}?>
    <td>{{$d_total}}</td>
</tr>
<tr>
    <td>SCADA</td>
    <?php $scada_total = 0;
    foreach($years_array as $year){ ?>
        <td>{{$scada_array_total[$year]}}</td>
    <?php $scada_total += $scada_array_total[$year];}?>
    <td>{{$scada_total}}</td>
</tr>
<tr>
    <td>Testing</td>
    <?php $t_total = 0;
    foreach($years_array as $year){ ?>
        <td>{{$testing_total[$year]}}</td>
    <?php $t_total += $testing_total[$year];}?>
    <td>{{$t_total}}</td>
</tr>
<tr>
    <td>Intern-Admin</td>
    <?php $ia_total = 0;
    foreach($years_array as $year){ ?>
        <td>{{$interns_admin_array_total[$year]}}</td>
    <?php $ia_total += $interns_admin_array_total[$year];}?>
    <td>{{$ia_total}}</td>
</tr>
    <?php }?>
<tr> <?php //If the chart unit is dollars, then we need to insert $ into the table. 
    if(!isset($chart_units) || $chart_units == 'dollars'){?>
    <td><b>Total</b></td>
    <?php foreach($years_array as $year){ ?>
        <td>${{$totals_array[$year]}}</td>
    <?php $grand_total_project += $totals_array[$year];
    }?>
    <td><b>${{$grand_total_project}}</b></td>
    <?php }else{?>
        <td><b>Total</b></td>
    <?php foreach($years_array as $year){ ?>
        <td>{{$totals_array[$year]}}</td>
    <?php $grand_total_project += $totals_array[$year];
    }?>
    <td><b>{{$grand_total_project}}</b></td>
    <?php } ?>
</tr>
</table>
</div>
<?php }} // Project Summary Bottom
    if($has_projects != true){ ?>
        <h4 style="text-align: center;">No projects associated with the provided Code.</h4>
    <?php } 
} ?>
@endsection