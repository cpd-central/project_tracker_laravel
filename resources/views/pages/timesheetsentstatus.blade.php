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
          <th><h5>Timesheet Sent?</h5></th>
      </tr>
    </thead>
@stop

@section('table-content')
<tbody>
    @csrf
@foreach($timesheets as $timesheet)
    <tr style="background-color:#fff;">
	<?php if ($timesheet['user'] == null) {
		continue;
	} ?>
	
	@foreach($users as $user)
		<?php if ($timesheet['user'] == $user['email']) { ?>
			<td align="center"><h5>{{ $user['name'] }}</h5></td>
		<?php } ?>
	@endforeach 
	<?php if ($timesheet['pay_period_sent'] == 1){ ?>
		<td style="background-color:#0f0;" align="center"><h5>Yes</h5></td>
	<?php } else { ?>
		<td style="background-color:#f00;" align="center"><h5>No</h5></td>
	<?php } ?>
	</tr>
@endforeach
</tbody>
@stop
</table>