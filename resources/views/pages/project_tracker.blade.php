
<head>

<div class = container2>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <script src="http://export.dhtmlx.com/gantt/api.js"></script>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
 
    <style type="text/css">
        html, body{
            height:100%;
            padding:0px;
            margin:0%;
            overflow: hidden;
        }

    </style>
    <div class = container3>
    <style>
    .container3 {
        margin-left: 18%;
        margin-right: 0;
    }
    </style>
    @include('includes.navbar')
    </div>
</div>
</head>

<div class = row>
    <div class = "container1">
    <style>
        .container1 {
            width: 10%;
            margin-top:7%;
            margin-left:20%;
            margin-right:0px;
        }
        .table{
            overflow-y:scroll;
            height:475px;
            display:block;
            border: 2px solid black;
            width: 150%;
            }
    </style>
    <label for="reference_list" style = "font-size:1.5em"><b>Reference List:  </b> </label>
    <table class="table" id="reference_list">
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


    <div class="container">
        <style>
        .container {
            margin-left:4%;
            margin-right:0%;
            width: 90%;
        }
        </style>
        <form class="form-inline md-form mr-auto mb-4" method="post" action="{{ route('pages.project_tracker') }}"> 
            @csrf
            <div id="decoration" style='width:12%; height:10%; "background-color:#cccccc;' ></div>
            <div class="column">
                <label for="CurrentProject"><b>Current Project Code:  </b> </label>
                <input type = "text" id = "CurrentProject" name = "CurrentProject" value = {{$CurrentProject}} >
            <div id="decoration" style='width:12%; height:10%; "background-color:#cccccc;' ></div>
            <label for="CurrentProject" ><b>Current Project Total Time: </b> </label>
                <div><p>
                <?php
                if ((!is_null($project_array)) && (sizeof($project_array) > 0)){
                    echo "<br>";
                    echo round(((time()-$project_array[array_keys($project_array)[(sizeof($project_array)-1)]])/60));
                    echo ' minutes';
                } else {

                }
                ?>
                </p></div> 
            </div>
            <button style="width:135px;height:135px;" type= "submit" class ="button1" id = "StartDay" > Enter Project</button>
            <style>
            .button1 {
            background-color: #ddd;
            border: 2px solid black;
            color: Black;
            padding: 15px 20px;
            text-align: center;
            text-decoration: wavy;
            display: inline-block;
            margin: 200px 75px;
            cursor: pointer;
            border-radius: 50%;
            font: bold 17px arial, helvetica, sans-serif;
            background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(180,235,225,1)), to(rgba(200,235,200,13)));
            display: inline-block;
            } 
            </style>
        </form>
        <form class="form2" method="post"> 
            @csrf
            <button style="width:135px;height:135px;" type= "submit" class ="button2" id = "TimesheetSubmit" onclick = "timesheetSubmit()"> Submit to Timesheet</button>
            <script>
            function timesheetSubmit(){
                dd("Hey");
            }
            </script>
            <style>
            .button2 {
            background-color: #ddd;
            border: 2px solid black;
            color: Black;
            padding: 15px 20px;
            text-align: center;
            text-decoration: wavy;
            display: inline-block;
            margin: 0% 0%;
            cursor: pointer;
            border-radius: 50%;
            font: bold 17px arial, helvetica, sans-serif;
            background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(180,235,225,1)), to(rgba(200,235,200,13)));
            display: inline-block;
            }
            </style>
        </form>
        
        <style>
        form {
            width: 70%;
            height: 50%;
            margin-left: 0%;
            margin-right: 0%;
            position: relative;
            }
        .form1{
            margin-left: 0%;
            margin-bottom: 0px;
            width: 40%;
            height: 450px;
            position: relative;
        }
        .form2{
            float: left;
            margin-left: 10%;
            margin-right: 0%;
            margin-bottom: 0%;
            margin-top: 0%;
            width: 20%;
            height: 20%;
            position: relative;
        }
        </style>
        <div class = row style= 'width: 55%'>
            <style>
                table {text-align: center;}
            </style>
                <table style="width:70%; border: 1px solid black;">
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
        </div>
        <form class="form1" method="post" action="{{ route('pages.project_tracker') }}">
            @csrf
            <div class = row style= 'width: 600px'>
            <div id="decoration3" style='width:200px; height:100px; "background-color:#cccccc;' ></div>
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
                    padding: 10px 25px;
                    text-align: justify;
                    display: inline-block;
                    margin: 20px 0%;
                    cursor: pointer;
                    border-radius: 0%;
                    font: bold 12px arial, helvetica, sans-serif;
                    background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(225,200,200,1)), to(rgba(245,230,200,13)));
                    display: inline-block;
                } 
                .form1 {
                    width: 50px;
                    height: 200px;
                    margin-left: 37%;
                    position: relative;
                }
                .column {
                    width: 200px;
                    height: 200px;
                }
                </style>
                <button style="width:justify;height:justify;" type= "submit" class ="button" id = "Delete" value = "Delete" name = "Delete"> Delete Last Project</button>
            </div>
        </form>
    </div>
</div>