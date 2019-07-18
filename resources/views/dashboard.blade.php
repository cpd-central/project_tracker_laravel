<?php use App\Timesheet;
$collection = Timesheet::where('user', auth()->user()->email)->get();
if(!$collection->isEmpty()){
    $timesheet = $collection[0];
}
else{
    $timesheet = null;
}?>

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
height: 370px;
margin-top: auto;
margin-bottom: auto;
width: 400px;
background-color: rgba(0,0,0,0.5) !important;
}


.card-header h3{
color: white;
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

.remember{
color: white;
}

.remember input
{
width: 20px;
height: 20px;
margin-left: 15px;
margin-right: 5px;
}

.login_btn{
color: black;
background-color: #FFC312;
width: 200px;
}

.login_btn:hover{
color: black;
background-color: white;
}

.links{
color: white;
}

.links a{
margin-left: 4px;
}
</style>
<head>
	<title>Dashboard</title>
   <!--Made with love by Mutiullah Samim -->
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Dashboard</h3>
			</div>
			<div class="card-body">
					<div class="form-group">
                        <a href={{ route('pages.projectindex') }} class="btn login_btn">Project Index</a>
                    </div>
                    <div class="form-group">
                        <a href={{ route('pages.timesheet', $timesheet['_id']) }} class="btn login_btn">Timesheet</a>
                    </div>
                    <div class="form-group">
                        <a href={{ route('logout') }} class="btn login_btn">Logout</a>
                    </div>
			</div>
			<div class="card-footer">				
			</div>
		</div>
	</div>
</div>
</body>
</html>
