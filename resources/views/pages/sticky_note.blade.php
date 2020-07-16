<!DOCTYPE html>

<?php 
use App\User; 
$employees = User::all();
?>
<head>
    @include('includes.navbar')
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
            margin:0px;
            overflow: hidden;
        }

    </style>
</head>
<body>
<div id="gantt_here" style='width:100%; height:70%;'></div>
<br>
<form method="post">
@csrf
<button style="margin:10px;" type="button" class="btn btn-primary" id="thirdlevel">Expand All</button>
<button type="button" class="btn btn-primary" id="secondlevel">Expand One Level</button>
<button style="margin:10px;" type="button" class="btn btn-primary" id="firstlevel">Contract All</button>
@if($filtered == false)
<button style="margin:10px;" type="submit" class="btn btn-warning" id="loadyourprojects">Load Your Projects</button>
<input type="hidden" id="loaded" name="loaded" value="all" readonly />
@else
<button style="margin:10px;" type="submit" class="btn btn-warning" id="loadallprojects">Load All Projects</button>
<input type="hidden" id="loaded" name="loaded" value="your" readonly />
@endif
<button style="margin:10px;" type="button" class="btn btn-success" id="export">Export To Excel</button>
</form>
<form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.sticky_note') }}"> 
    @csrf
    <select id="employeesearch" name='employeesearch'>
        <option @if(!isset($term))selected @endif>Filter:</option>
        <option @if(isset($term) && $term == 'SCADA')selected @endif value="SCADA">SCADA</option>
        <option @if(isset($term) && $term == 'drafting')selected @endif value="drafting">Drafter</option>
        <option @if(isset($term) && $term == 'senior')selected @endif value="senior">Senior</option>
        <option @if(isset($term) && $term == 'project')selected @endif value="project">Project Manager</option>
        <option @if(isset($term) && $term == 'interns-admin')selected @endif value="interns-admin">Interns-Admins</option>
        @foreach($employees as $employee)
            <option @if(isset($term) && $term == $employee->name)selected @endif value="<?=$employee->name?>"><?=$employee->name?></option>
        @endforeach
    </select>
    <button style="margin:10px;" type="submit" class="btn btn-warning" id="filter">Filter</button>
</form>
<script type="text/javascript">
    gantt.config.readonly = true;


    gantt.config.layout = {
    css: "gantt_container",
    rows:[
        {
           cols: [
            {
              // the default grid view  
              view: "grid",  
              scrollX:"scrollHor", 
              scrollY:"scrollVer"
            },
            {
              // the default timeline view
              view: "timeline", 
              scrollX:"scrollHor", 
              scrollY:"scrollVer"
            },
            {
              view: "scrollbar", 
              id:"scrollVer"
            }
        ]},
        {
            view: "scrollbar", 
            id:"scrollHor"
        }
    ]
}

    // Column labels on the left side of the Gantt Chart
    gantt.config.columns =  [
    {name:"text",   label:"Project Name",   align:"left", width: 250, tree:true},
    {name:"end_date",   label:"End date",   align:"center", width: 75 },
    {name:"name_1",   label:"Employee 1",   align:"center", width: 100 },
    {name:"name_2",   label:"Employee 2",   align:"center", width: 100 },
    ];


    gantt.config.xml_date = "%Y-%m-%d";
    
    gantt.config.scales = [
    {unit: "month", step: 1, format: "%F, %Y"},
    {unit: "day", step: 1, format: "%j, %D"}
    ];
    
    //pushes all the json data into the data array so javascript has access to the data
    var data = [];
    <?php for ($i = 0; $i < count($json); $i++) {?>
        <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
            data.push(<?php echo $json[$i][$j]?>);
        <?php } ?>
     <?php } ?>
    //puts the data back into a json format
    var project = {};
    project={"data": data};
    
    //initializes and displays the gantt chart with the data given
    gantt.init("gantt_here");
    gantt.parse(project);
    

    //opens the chart to the second level by default
    <?php for ($i = 0; $i < count($json); $i++) {?>
        var currentjson = <?php echo $json[$i][0] ?>;
        gantt.open(currentjson.id);
    <?php } ?>

    $(document).ready(function() {
        $("#thirdlevel").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    gantt.open(currentjson.id);
                <?php } ?>
            <?php } ?>
        });

        $("#secondlevel").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    gantt.close(currentjson.id);
                <?php } ?>
                var currentjson = <?php echo $json[$i][0] ?>;
                gantt.open(currentjson.id);
            <?php } ?>
        });

        $("#firstlevel").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    gantt.close(currentjson.id);
                <?php } ?>
            <?php } ?>
        });

        $("#export").on('click', function() {
            var c = window.confirm("Would you like to export the Gantt Chart data to an Excel file?");
            if(c == true){
                gantt.exportToExcel(project);
            }
        });
        
        /*
        $("#loadyourprojects").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    console.log(currentjson.id);
                <?php } ?>
            <?php } ?>
        });
        */
    });

</script>

</body>