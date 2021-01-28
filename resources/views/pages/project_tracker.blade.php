<!DOCTYPE html>

<head>
    @include('includes.navbar')
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <script src="https://export.dhtmlx.com/gantt/api.js"></script>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
 
    <style type="text/css">
        html, body{
            height:100%;
            padding:0px;
            margin:0px;
            overflow: hidden;
        }

        h5{
            margin:0;
            padding:0;
        }

        button{
            margin:5px;
            padding:0;
        }

    </style>
</head>
<body>
    <div class = "row" style = "height:100%; width:100%; margin-top: 3%;">
        <div class = "container1">
            <style>
                .container1 {
                    width: 30%;
                    margin-top:7%;
                    margin-left:10%;
                    margin-right:0px;
                    margin:0;
                    padding:0;
                }
                .table1{
                    text-align:center;
                    overflow-y:scroll;
                    height:475px;
                    display:block;
                    border: 2px solid black;
                    width: 70%;
                    padding:0%;
                    margin-left:30%;
                    margin-top:10%;
                    }
            </style>
            <table class="table1" id="reference_list">
                <label for="reference_list" style = "font-size:1.5em; margin-left:10%; margin-top:3%"><b>Reference List:  </b> </label>
                        <?php 
                                for ($x = 0; $x <  sizeof($all_project_codes); $x++) {
                                    echo "<tr>";
                                    echo "<td>";
                                    echo $all_project_codes[$x];
                                    echo "</td>";
                                    echo "<td>";
                                    echo $all_project_desc[$x];
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            ?>

            </table>
        </div>
        <div class="container5">
            <style>
            .container5 {
                margin-left:0%;
                margin-right:0%;
                margin-top:5%;
                height: 100%;
                width: 70%;
                margin:0;
                padding:0;
            }
            .row{
                display: flex;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
                height: 100%;
            }
            </style>
            <div class= "row" style = "width = 100% !important; height = 100% !important;">
                <div class="column1" style = "width = 50%; height = 100%;">
                    <form class="form6" method="post" action="{{ route('pages.project_tracker') }}">
                        @csrf
                        <div class= "column" style = "width:100%; height:60%;">
                            <div class="row" style = "width:100%; height:50%;">
                                <label for="CurrentProject" style = "font-size:1em; margin-left:8%; margin-top: 10%; width:50%;"><b>Current Project Code:  </b> </label>
                                <input type = "text" style = "margin-left:2%; margin-top: 10%; width:30%; height:40%" id = "CurrentProject" name = "CurrentProject" value = {{$CurrentProject}} >
                            </div>
                            <div class="column" style = "width:100%; height:50%;">
                                <label for="CurrentProject" style = "font-size:1.4em; margin-left:7%; margin-top: 8%; margin-right:60%; width:40%; height:50%; text-align:left;" ><b>Current Project Time: </b> </label>
                                <div class = "row" style = "width:40%; height:5%; margin-left:10%; margin-bottom:90%;">
                                    <?php
                                        if ((!is_null($project_array)) && (sizeof($project_array) > 0)){
                                            echo "<p> <font size=10>";
                                            echo round(((time()-$project_array[array_keys($project_array)[(sizeof($project_array)-1)]])/60));
                                            echo ' minute(s)';
                                            echo "</font> </p>";
                                        } else {
                                            ;
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <button style="width:30%;height:30%;margin-left:55%; margin-top:0%;" type= "submit" class ="button1" id = "StartDay" > Enter Project</button>
                        <style>
                        .button1 {
                        background-color: #ddd;
                        border: 2px solid black;
                        color: Black;
                        padding: 5px 5px;
                        text-align: center;
                        text-decoration: wavy;
                        display: inline-block;
                        margin: 20px 20px;
                        cursor: pointer;
                        border-radius: 30%;
                        font: bold 17px arial, helvetica, sans-serif;
                        background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(180,235,225,1)), to(rgba(200,235,200,13)));
                        display: inline-block;
                        } 
                        </style>
                    </form>
                    <form class="form5" method="post" action="{{ route('pages.project_tracker') }}">
                    <style>
                    .form6{
                        margin:0;
                        padding:0;  
                        margin-left: 0%;
                        margin-bottom: 0px;
                        width: 100%;
                        height: 20%;
                        position: relative;
                    }
                    .form5{
                        margin:0;
                        padding:0;  
                        margin-left: 0%;
                        margin-bottom: 0px;
                        width: 50%;
                        height: 10%;
                        position: relative;
                    }
                    </style>
                        @csrf
                            <button style="width:justify;height:justify;" type= "submit" class ="button2" id = "submitTimesheet" value = "submitTimesheet" name = "submitTimesheet"> Submit Timesheet</button>
                    </form>
                        <style>
                        .button2 {
                        background-color: #ddd;
                        border: 2px solid black;
                        color: Black;
                        padding: 5px 5px;
                        text-align: center;
                        text-decoration: wavy;
                        display: inline-block;
                        margin-left: 31%;
                        margin-top: 40%;
                        cursor: pointer;
                        border-radius: 10%;
                        font: bold 17px arial, helvetica, sans-serif;
                        background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(180,235,100,1)), to(rgba(200,235,200,13)));
                        display: inline-block;
                        }
                        </style>
                    
                        <style>
                        .table{
                        overflow-y:scroll;
                        height:475px;
                        display:block;
                        border: 2px solid black;
                        width: 25%;
                        padding:0%;
                        text-align: center; 
                        border-collapse: separate;
                        margin-left: 20% !important;
                        }
                        </style>
                            <table style="width:40%; padding:0%; margin-left:10%; margin-top:15%; text-align: center; border: 1px solid black;">
                                <tr>
                                <th>Project</th>
                                <th>Hours : Minutes</th>
                                <?php
                                if (!is_null($project_array)){
                                    for ($x = 0; $x < sizeof($project_array); $x++) {
                                        $array1 = array_keys($project_array);
                                        $array2 = array_values($project_array);
                                        echo "<tr>";
                                        echo "<td>";
                                        echo $array1[$x];
                                        echo "</td>";
                                        echo "<td>";
                                        if (sizeof($project_array) > 1 && $x < (sizeof($project_array) - 1)){
                                            if (isset($_POST['Delete']) && ($x + 2) == sizeof($project_array))
                                            {
                                            echo "<script type = 'text/javascript'> 
                                            window.location.replace(location)
                                            </script>" ;
                                            }
                                            echo (($array2[$x + 1] - $array2[$x]) - (($array2[$x + 1] - $array2[$x])%3600))/3600;
                                            echo ' : ';
                                            echo (($array2[$x + 1] - $array2[$x])/60)%60;
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                                
                            </table>
                    <form class="form6" method="post" action="{{ route('pages.project_tracker') }}">
                        @csrf
                            <style>
                            label {
                                text-align: right;
                                line-height:150%;
                                font-size:1em;
                                float: left;
                                display: inline-block;
                            }
                            .button {
                                background-color: #ddd;
                                border: 2px solid black;
                                color: Black;
                                width:auto;
                                padding: 10px 10px;
                                text-align: justify;
                                display: inline-block;
                                margin-left: 55%;
                                margin-top: 0%;
                                cursor: pointer;
                                border-radius: 0%;
                                font: bold 12px arial, helvetica, sans-serif;
                                background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(225,200,200,1)), to(rgba(245,230,200,13)));
                                display: inline-block;
                            }
                            .column1 {
                                width: 60%;
                                height: 100%;
                            }
                            .column2 {
                                width: 40%;
                                height: 100%;
                            }
                            </style>
                            <button style="width:justify;height:justify;" type= "submit" class ="button" id = "Delete" value = "Delete" name = "Delete"> Delete Last Project</button>
                    </form>
                </div>
                <div class="column2" style = "width = 50%; height = 100%;">
                    <form class="form6" method="GET" action="{{ route('pages.project_tracker') }}">
                    @csrf
                    <?php
                    $CurrentProjects = array();
                    foreach($overallTimesheet as $employee) {
                        if (isset($employee['Project_Hours'])){
                            $todaysProjects = array_keys($employee['Project_Hours']);
                            $projectName = end($todaysProjects);
                            if (in_array($projectName,array_keys($CurrentProjects))){
                                $CurrentProjects[$projectName] = (array)$CurrentProjects[$projectName];
                                array_push($CurrentProjects[$projectName],$employee['user']);
                            }
                            else{
                            $CurrentProjects[$projectName] = (array)$employee['user'];
                            }
                        }
                    }
                    
                    ?>
                    <table style="width:60%; padding:2%; margin-left:2%; margin-top:15%; text-align: center; border: 2px solid black; margin-right:2%; border-collapse: separate;">
                                <tr>
                                <th>Current Projects Being Worked On: </th>
                                <?php foreach (array_keys($CurrentProjects) as $project){
                                        echo "<tr>";
                                        echo "<td>";
                                        if ($project != '0'){
                                            echo ($project);
                                        }
                                        echo "</td>";
                                        echo "<td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                                </tr>
                        </table>
                    <div class="row" style = "width:100%; height:50%; margin-left:0%;">
                            <label for="spectatedProject" style = "font-size:1em; margin-left:0%; margin-top: 10%; width:20%; text-align:left;"><b>Spectating Code: </b> </label>
                            <input type = "text" style = "margin-left:2%; margin-top: 10%; width:20%; height:40%" id = "spectatedProject" name = "spectatedProject" value = {{$spectatedProject}} >
                    </div>
                    <button style="width:30%;height:30%;margin-left:18%; margin-top:5%;" type= "submit" class ="button1" id = "SeeEmployees" > Enter Project</button>
                    <table style="width:60%; padding:2%; margin-left:2%; margin-top:15%; text-align: center; border: 2px solid black; margin-right:2%; border-collapse: separate;">
                            <tr>
                            <th>Employees Working on Project: </th>
                            <?php foreach (array_keys($CurrentProjects) as $project){
                                    if ($project == $spectatedProject and $project != '0'){
                                        if (sizeof($CurrentProjects) > 0){
                                                echo "<tr>";
                                                echo "<td>";
                                                echo "<pre>";
                                                foreach($CurrentProjects[$project] as $result) {
                                                    echo $result, '<br>';
                                                }
                                                echo "</pre>";
                                                echo "</td>";
                                                echo "<td>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } 
                                
                             ?>
                            </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>