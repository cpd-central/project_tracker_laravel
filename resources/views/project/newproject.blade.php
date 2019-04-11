@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">New Project</div>
						
				<div class="card-body">
					<?php echo 'hi'; ?>	
					<form action="{{ route('project.save') }}" method="post">	
						@csrf
						<div class="form-group">
							<label for="projectname">Project Name:</label>
							<input type="text" class="form-control" name="projectname">
						</div>
						<div class="form-group">
							<label for="clientcontactname">Client Contact Name:</label>
							<input type="text" class="form-control" name="clientcontactname">
						</div>
						<div class="form-group">
							<label for="clientcompany">Client Company:</label>
							<input type="text" class="form-control" name="clientcompany">
						</div>
						<div class="form-group">
							<label for="cegproposalauthor">CEG Proposal Author:</label>
							<input type="text" class="form-control" name="cegproposalauthor">
						</div>
						<div class="form-group">
							<label for="mwsize">MW Size (AC):</label>
							<input type="text" class="form-control" name="mwsize">
						</div>
						<div class="form-group">
							<label for="voltage">Voltage:</label>
							<input type="text" class="form-control" name="voltage">
						</div>
						<div class="form-group">
							<label for="dollarvalue">Dollar Value:</label>
							<input type="text" class="form-control" name="dollarvalue">
						</div>
						<div class="form-group">
							<label for="datesent">Date Sent (YYYY-MM-DD):</label>
							<input type="text" class="form-control" name="datesent">
						</div>
						<div class="form-group">
							<label for="startdate">Start Date (YYYY-MM-DD):</label>
							<input type="text" class="form-control" name="startdate">
						</div>
						<div class="form-group">
							<label for="enddate">End Date (YYYY-MM-DD):</label>
							<input type="text" class="form-control" name="enddate">
						</div>
						<div class="form-group">
							<label for="projectstatus">Project Status:</label>
							<input type="text" class="form-control" name="projectstatus">
						</div>
						<div class="form-group">
							<label for="projecttype">Project Type:</label>
							<input type="text" class="form-control" name="projecttype">
						</div>
						<div class="form-group">
							<label for="epctype">EPC Type:</label>
							<input type="text" class="form-control" name="epctype">
						</div>
						<p align="center"><button class="btn btn-primary">Save</button></p>	
					</form>	
				</div>
			</div>
		</div>
	</div>
</div>
@endsection











