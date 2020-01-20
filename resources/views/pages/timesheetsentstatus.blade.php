<!DOCTYPE html>

@extends('layouts.index')

@section('page-title', 'Timesheet Sent Status')
@section('table-title', 'Timesheet Sent Status')
@section('page-title', 'Timesheet Sent Status')

@section('table-header')

    <table class="table table-striped">
    <thead style="text-align:center">
      <tr>
          <th>User</th>
          <th>Timesheet Sent?</th>
      </tr>
    </thead>
@stop

@section('table-content')
<tbody>
    @csrf
@foreach($timesheets as $timesheet)
    <tr>
	<?php if ($timesheet['user'] == null) {
		continue;
	} ?>
	
	@foreach($users as $user)
		<?php if ($timesheet['user'] == $user['email']) { ?>
			<td align="center">{{ $user['name'] }}</td>
		<?php } ?>
	@endforeach 
    
	<?php if ($timesheet['pay_period_sent'] == 1){ ?>
		<td style="background-color:#0f0;" align="center">Yes</td>
	<?php } else { ?>
		<td style="background-color:#f00;" align="center">No</td>
	<?php } ?>
	</tr>
@endforeach
</tbody>
@stop
</table>