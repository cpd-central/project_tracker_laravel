<!DOCTYPE html>

@extends('layouts.index')

@section('page-title', 'Timesheet Sent Status')
@section('table-title', 'Timesheet Sent Status')
@section('page-title', 'Timesheet Sent Status')

@section('table-header')

    <table class="table table-striped">
    <thead style="text-align:center">
      <tr>
          <th><h5>User</h5></th>
          <th><h5>Hours From Last Pay Period</h5></th>
		  <th><h5>Last update on tracker.ceg.mn</h5></th>
      </tr>
    </thead>
@stop

@section('table-content')
<tbody>
    @csrf
@foreach($users as $user)
    <tr style="background-color:#fff;">
		<td align="center"><h5>{{ $user['name'] }}</h5></td>
	@foreach($timesheets as $timesheet)
	<?php if ($timesheet['user'] == null) {
		continue;
	} else if ($timesheet['user'] == $user['email'])  {?>
	<?php if ($timesheet['pay_period_total'] >=80){ ?>
		<td style="background-color:#0f0;" align="center"><h5>>= 80 hrs</h5></td>
	<?php } else { ?>
		<td style="background-color:#f00;" align="center"><h5><?php echo $timesheet['pay_period_total']?> hrs</h5></td>
	<?php } ?>
		<td align="center"><h5><?php echo $timesheet['updated_at']?></h5></td>	
	<?php } ?>
	@endforeach
	</tr>
@endforeach
</tbody>
@stop
</table>