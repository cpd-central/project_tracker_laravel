<?php 
use App\Timesheet;
use App\Project;
use App\User;
//Gets the value for how many days it has been since the user has filled their timesheet
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

//gets the users upcoming due dates
$username =  auth()->user()->name;
$projects = Project::all()->where('projectstatus', 'Won');
$today = new \DateTime("now", new \DateTimeZone("UTC"));
$userprojects = [];
$userduedates = [];
$usertasknames = [];
$usertaskdesc = [];
foreach($projects as $project){
	if (isset($project['duedates'])){
		$duedates = $project['duedates'];
		$keys = array_keys($duedates);
		$count = 0;
		foreach($duedates as $duedate){
			if($keys[$count] != 'additionalfields'){
				if (isset($duedate['person2'])){
					//if the user is working on the current major deliverable, calculate the days until due
					if($duedate['person1'] == $username || $duedate['person2'] == $username){
						array_push($userprojects, $project);
						if($duedate['due'] == "None" || $duedate['due']->toDateTime() < $today){
							$daystodue = 999;
						}
						else{
							$due = $duedate['due']->toDateTime();
							$daystodue = date_diff($today, $due)->format("%a");
						}
						//adds the days until the due date, the name of the task, and the description to their given arrays
						array_push($userduedates, $daystodue);
						array_push($usertasknames, $keys[$count]);
						array_push($usertaskdesc, "");
					}
				}
				else{
					//if the user is working on the current major deliverable, calculate the days until due
					if($duedate['person1'] == $username){
						array_push($userprojects, $project);
						if($duedate['due'] == "None" || $duedate['due']->toDateTime() < $today){
							$daystodue = 999;
						}
						else{
							$due = $duedate['due']->toDateTime();
							$daystodue = date_diff($today, $due)->format("%a");
						}
						//adds the days until the due date, the name of the task, and the description to their given arrays
						array_push($userduedates, $daystodue);
						array_push($usertasknames, $keys[$count]);
						array_push($usertaskdesc, "");
					}
				}
				$subkeys = array_keys($duedate);
				$subcount = 0;
				foreach($duedate as $task){
					$taskname = $subkeys[$subcount];
					if($taskname != "person1" && $taskname != "person2" && $taskname != "due"){
						if (isset($task['person2'])){
							//if the user is involved in the current minor deliverable, calculate the days until it is due
							if($task['person1'] == $username || $task['person2'] == $username){
								array_push($userprojects, $project);
								if($task['due'] == "None" || $task['due']->toDateTime() < $today){
									$daystodue = 999;
								}
								else{
									$due = $task['due']->toDateTime();
									$daystodue = date_diff($today, $due)->format("%a");
								}
								//adds the days until the due date, the name of the task, and the description to their given arrays
								array_push($userduedates, $daystodue);
								array_push($usertasknames, $keys[$count]);
								array_push($usertaskdesc, $taskname);
							}
						}
						else{
							//if the user is involved in the current minor deliverable, calculate the days until it is due
							if($task['person1'] == $username){
								array_push($userprojects, $project);
								if($task['due'] == "None" || $task['due']->toDateTime() < $today){
								$daystodue = 999;
								}
								else{
									$due = $task['due']->toDateTime();
									$daystodue = date_diff($today, $due)->format("%a");
								}
								//adds the days until the due date, the name of the task, and the description to their given arrays
								array_push($userduedates, $daystodue);
								array_push($usertasknames, $keys[$count]);
								array_push($usertaskdesc, $taskname);
							}
						}
					}
					//exception for the communication minor deliverable in SCADA because it's the only minor deliverable with other minor deliverables
					if($taskname == "Communication"){
						$comkeys = array_keys($task);
						$comcount = 0;
						foreach($task as $communicationtask){
							$comname = $comkeys[$comcount];
							if($comname != "person1" && $comname != "person2" && $comname != "due"){
								//if the user is involved in the current communication minor deliverable, calculate the days until it is due
								if($communicationtask['person1'] == $username || $communicationtask['person2'] == $username){
									array_push($userprojects, $project);
									if($communicationtask['due'] == "None" || $communicationtask['due']->toDateTime() < $today){
									$daystodue = 999;
									}
									else{
										$due = $communicationtask['due']->toDateTime();
										$daystodue = date_diff($today, $due)->format("%a");
									}
									//adds the days until the due date, the name of the task, and the description to their given arrays
									array_push($userduedates, $daystodue);
									array_push($usertasknames, $keys[$count]);
									array_push($usertaskdesc, "".$subkeys[$subcount]." ".$comkeys[$comcount]);
								}
							}
							$comcount++;
						}
					}
					$subcount++;
				}
			}
			else{
				//repeats steps above but for any additional fields that the user may be a part of
				$addedkeys = array_keys($duedate);
				$addedcount = 0;
				foreach($duedate as $additionalfield){
					$addedname = $addedkeys[$addedcount];
					if($additionalfield['person1'] == $username || $additionalfield['person2'] == $username){
						array_push($userprojects, $project);
						if($additionalfield['due'] == "None" || $additionalfield['due']->toDateTime() < $today){
							$daystodue = 999;
						}
						else{
							$due = $additionalfield['due']->toDateTime();
							$daystodue = date_diff($today, $due)->format("%a");
						}
						array_push($userduedates, $daystodue);
						array_push($usertasknames, $addedkeys[$addedcount]);
						array_push($usertaskdesc, "");
					}
					$subkeys = array_keys($additionalfield);
					$subcount = 0;
					foreach($additionalfield as $task){
						$taskname = $subkeys[$subcount];
						if($taskname != "person1" && $taskname != "person2" && $taskname != "due"){
							if($task['person1'] == $username || $task['person2'] == $username){
								array_push($userprojects, $project);
								if($task['due'] == "None" || $task['due']->toDateTime() < $today){
									$daystodue = 999;
								}
								else{
									$due = $task['due']->toDateTime();
									$daystodue = date_diff($today, $due)->format("%a");
								}
								array_push($userduedates, $daystodue);
								array_push($usertasknames, $addedkeys[$addedcount]);
								array_push($usertaskdesc, $taskname);
							}
						}
						$subcount++;
					}
					$addedcount++;
				}
			}
			$count++;
		}
	}
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
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.projectindex') }} class="btn login_btn">Project Index</a>
					</div>
					
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.planner') }} class="btn login_btn">Project Planner</a>
					</div>
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.timesheet') }} class="btn login_btn">Timesheet</a>
					</div>
					<!--Corey adding link for timesheet sent status page-->
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.timesheetsentstatus') }} class="btn login_btn">Timesheet Sent Status</a>
                    </div>
					<div class="form-group" style="{{$style_string}}">
                        <a href={{ route('pages.bdb') }} class="btn login_btn">BDB</a>
					</div>
					<!--End Corey new code-->
					<?php if (auth()->user()->role == "sudo" || auth()->user()->role == "admin") { ?>
					<div class="form-group" style="{{$style_string}}">
						<a href={{ route('pages.accountdirectory') }} class="btn login_btn">Account Directory</a>
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
		<?php if(sizeof($userduedates) > 0){ ?>
		<div class="card">
			<div class="card-header">
				<h3>Upcoming Due Dates:</h3>
			</div>
			<div class="card-body">
				<?php for($i = 0; $i < sizeof($userprojects); $i++) { ?>
					<?php if($userduedates[$i] <= 14){ ?>
						<br>
						<?php if($usertasknames[$i] == "studies"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } elseif($usertasknames[$i] == "physical"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} Physical Drawing Package {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } elseif($usertasknames[$i] == "control"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} Wiring and Controls Drawing Package {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } elseif($usertasknames[$i] == "collection"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} Collection Drawing Package {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } elseif($usertasknames[$i] == "transmission"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} Transmission Drawing Package {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } elseif($usertasknames[$i] == "scada"){ ?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} SCADA {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } else {?>
							<label style="color:white;">{{$userprojects[$i]['projectname']}} {{$usertasknames[$i]}} {{$usertaskdesc[$i]}} is due in {{$userduedates[$i]}} days.</label>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
</body>
</html>
