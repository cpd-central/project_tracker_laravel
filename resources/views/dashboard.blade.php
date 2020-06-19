<?php use App\Timesheet;
$collection = Timesheet::where('user', auth()->user()->email)->get();
if(!$collection->isEmpty()){
	$timesheet = $collection[0];
	$last_update = $timesheet['updated_at'];
	$today = new \DateTime("now", new \DateTimeZone("UTC"));
	$diff = $today->diff($last_update);
}
else{
    $timesheet = null;
}
?>

<!DOCTYPE html>
<html>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Numans');

html,body{
background-image: url('../img/Oak Tree Wind Sunset (6).JPG');
background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Numans', sans-serif;
}

.container{
height: 100%;
align-content: center;
}

.card{
margin-top: auto;
margin-bottom: auto;
width: 400px;
background-color: rgba(0,0,0,0.5) !important;
}

.card-header h3{
color: white;
text-align: center;
}

.card-footer {
background-color: rgba(0, 0, 0, 0.25) !important;
}

.form-group{
text-align: center;
}

.input-group-prepend span{
width: 50px;
background-color: #FFC312;
color: black;
border:0 !important;
}

input:focus{
outline: 0 0 0 0  !important;
box-shadow: 0 0 0 0 !important;
}

.login_btn{
color: black;
background-color: #FFC312;
width: 200px;
}

.bill_btn{
color: black;
background-color: rgb(230, 187, 255);
width: 200px;
}

.bill_btn:hover{
color: black;
background-color: rgb(245, 228, 255);
}

.login_btn:hover{
color: black;
background-color: white;
}
</style>
<head>
	<title>Dashboard</title>
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

</head>

<?php
if(auth()->user()->role == "proposer" || auth()->user()->role == "admin"){
	$style_string = "height:15%";
}
elseif(auth()->user()->role == "sudo"){
	$style_string = "height:9%";
}
else{
	$style_string = "height:35%";
}
?>

<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card text-center">
			<div class="card-header">
				<h3>Dashboard</h3>
			</div>
			<div class="card-body">
				<?php if(auth()->user()->role != "user") {?>
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.projectindex') }} class="btn login_btn">Project Index</a>
					</div>
					
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.planner') }} class="btn login_btn">Project Planner</a>
					</div>
					
					<?php } ?>
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.timesheet') }} class="btn login_btn">Timesheet</a>
					</div>
					<!--Corey adding link for timesheet sent status page-->
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.timesheetsentstatus') }} class="btn login_btn">Timesheet Sent Status</a>
                    </div>
					<!--End Corey new code-->
					<?php if(auth()->user()->role == "drafting_manager") {?>
					<div class="form-group" style="{{$style_string}}">
						<a class="nav-link" href="{{ route('pages.drafterhours', ['drafter_page' => true]) }}">Drafter Hours</a>
					</div>
					<?php } elseif (auth()->user()->role == "sudo") { ?>
					<div class="form-group" style="{{$style_string}}">
						<a href={{ route('pages.roles') }} class="btn login_btn">Account Directory</a>
					</div>	
					<?php } ?>               
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('logout') }} class="btn login_btn" onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">Logout</a>
					</div>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					  </form>
			</div>
			@if(isset($timesheet) && $diff->d > 3)
			<div class="card-footer">
				<label style="color:white;">Reminder: It has been {{$diff->d}} days since you filled</label>
				<label style="color:white;">your timesheet. Please do so when convenient.</label>
			</div>
			@endif
		</div>
		<?php if(auth()->user()->role != "user" && !empty($billing)) {?>
		<div class="card">
			<div class="card-header">
				<h3>Billing Widget</h3>
			</div>
			<div class="card-body">
					<div class="form-group" style="{{$style_string}}">
                        <table>
							<tr>
								<a href={{ route('pages.hoursgraph') }} class="btn bill_btn">Edit Billing for Projects</a>
							</tr>
							@foreach($billing as $bill)
							<tr>
								<td style="color:white;">
									{{$bill['projectname']}} billing is due.
								</td>
							</tr>
							@endforeach
						</table>
                    </div>
			</div>
		</div>
	<?php } ?>
	</div>

</div>
</body>
</html>
