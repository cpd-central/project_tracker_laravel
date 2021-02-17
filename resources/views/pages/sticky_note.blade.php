<!DOCTYPE html>

<?php
use App\User; 
$employees = User::all();
$employeesort = [];
foreach($employees as $employee){
  array_push($employeesort, $employee->name);
}
sort($employeesort);
$hold = [];
for($i = 0; $i < sizeof($employeesort); $i++){
  foreach($employees as $employee){
    if($employee->name == $employeesort[$i]){
      array_push($hold, $employee);
    }
  }
}
$employees = $hold;
?>
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
<div id="gantt_here" style='width:100%; height:68%;'></div>
<br>
<form method="post">
@csrf
<button type="button" class="btn btn-primary" id="thirdlevel">Expand All</button>
<button type="button" class="btn btn-primary" id="secondlevel">Expand One Level</button>
<button type="button" class="btn btn-primary" id="firstlevel">Contract All</button>
<button type="button" class="btn btn-secondary" id="zoomout">Zoom Out</button>
<button type="button" class="btn btn-secondary" id="zoomin">Zoom In</button>
@if($filtered == false)
<button type="submit" class="btn btn-warning" id="loadyourprojects">Load Your Projects</button>
<input type="hidden" id="loaded" name="loaded" value="all" readonly />
@else
<button type="submit" class="btn btn-warning" id="loadallprojects">Load All Projects</button>
<input type="hidden" id="loaded" name="loaded" value="your" readonly />
@endif
<button type="button" class="btn btn-success" id="export">Export To Excel</button>
</form>
<h5>Please filter by one category at a time<h5>
<form class="form-inline md-form mr-auto mb-4" method="get" action="{{ route('pages.sticky_note') }}"> 
    @csrf
    <h5>Employee/Category:&nbsp;&nbsp;</h5>
    <select id="employeesearch" name='employeesearch'>
        <option @if(!isset($term))selected @endif>No Filter</option>
        <option @if(isset($term) && $term == 'SCADA')selected @endif value="SCADA">SCADA</option>
        <option @if(isset($term) && $term == 'drafting')selected @endif value="drafting">Drafter</option>
        <option @if(isset($term) && $term == 'senior')selected @endif value="senior">Senior</option>
        <option @if(isset($term) && $term == 'project')selected @endif value="project">Project Manager</option>
        <option @if(isset($term) && $term == 'interns-admin')selected @endif value="interns-admin">Interns-Admins</option>
        @foreach($employees as $employee)
            <option @if(isset($term) && $term == $employee->name)selected @endif value="<?=$employee->name?>"><?=$employee->name?></option>
        @endforeach
    </select>
    <h5>&nbsp;&nbsp;Major Deliverables:&nbsp;&nbsp;</h5>
    <select id="secondlevelfilter" name='secondlevelfilter'>
        <option @if(!isset($major))selected @endif>No Filter</option>
        <option @if(isset($major) && $major == 'scada')selected @endif value="scada">SCADA</option>
        <option @if(isset($major) && $major == 'studies')selected @endif value="studies">Studies</option>
        <option @if(isset($major) && $major == 'physical')selected @endif value="physical">Physical Drawing Package</option>
        <option @if(isset($major) && $major == 'control')selected @endif value="control">Wiring and Controls Drawing Package</option>
        <option @if(isset($major) && $major == 'collection')selected @endif value="collection">Collection Line Drawing Package</option>
        <option @if(isset($major) && $major == 'transmission')selected @endif value="transmission">Transmisson Line Drawing Package</option>
    </select>
    <h5>&nbsp;&nbsp;Minor Deliverables:&nbsp;&nbsp;</h5>
    <select id="thirdlevelfilter" name='thirdlevelfilter'>
        <option @if(!isset($minor))selected @endif>No Filter</option>
        <option @if(isset($minor) && $minor == '90')selected @endif value="90">90%</option>
        <option @if(isset($minor) && $minor == 'IFC')selected @endif value="IFC">IFC</option>
    </select>
    <button style="margin:10px;" type="submit" class="btn btn-warning" id="filter">Filter</button>
</form>

<script type="text/javascript">
    //sets the gantt chart to read only
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


    const zoomModule = gantt.ext.zoom;

    zoomModule.init({
        levels: [
        {
            name:"day",
            scale_height: 27,
            min_column_width:80,
            scales:[
                {unit: "month", step: 1, format: "%F, %Y"},
                {unit: "day", step: 1, format: "%d %M"}
            ]
        },
        {
            name:"week",
            scale_height: 50,
            min_column_width:50,
            scales:[
            {unit: "week", step: 1, format: function (date) {
            var dateToStr = gantt.date.date_to_str("%d %M");
            var endDate = gantt.date.add(date, -6, "day");
            var weekNum = gantt.date.date_to_str("%W")(date);
            return "#" + weekNum + ", " + dateToStr(date) + " - " + dateToStr(endDate);
            }},
            {unit: "day", step: 1, format: "%j %D"}
            ]
        },
        {
            name:"month",
            scale_height: 50,
            min_column_width:120,
            scales:[
                {unit: "month", format: "%F, %Y"},
                {unit: "week", format: "Week #%W"}
            ]
            },
            {
            name:"quarter",
            height: 50,
            min_column_width:90,
            scales:[
            {unit: "month", step: 1, format: "%M"},
            {
            unit: "quarter", step: 1, format: function (date) {
                var dateToStr = gantt.date.date_to_str("%M");
                var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
                return dateToStr(date) + " - " + dateToStr(endDate);
            }
            }
            ]},
            {
            name:"year",
            scale_height: 50,
            min_column_width: 30,
            scales:[
                {unit: "year", step: 1, format: "%Y"}
            ]}
        ]
    });

    // Column labels on the left side of the Gantt Chart
    gantt.config.columns =  [
    {name:"text",   label:"Project Name",   align:"left", width: 250, tree:true},
    {name:"end_date",   label:"End date",   align:"center", width: 75 },
    {name:"name_1",   label:"Employee 1",   align:"center", width: 100 },
    {name:"name_2",   label:"Employee 2",   align:"center", width: 100 },
    ];

    //sets the layout and scale/range of the dates on the gantt chart
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
    console.log(project);
    gantt.parse(project);
    

    //opens the chart to the second level by default
    <?php for ($i = 0; $i < count($json); $i++) {?>
        var currentjson = <?php echo $json[$i][0] ?>;
        gantt.open(currentjson.id);
    <?php } ?>
 


    $(document).ready(function() {
        //opens all of the folders on the gantt chart so every level can be seen
        $("#thirdlevel").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    gantt.open(currentjson.id);
                <?php } ?>
            <?php } ?>
        });

        //opens the first set of folders on the gantt chart so the first and second level can be seen
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

        //closes all the folders so only the first level (project names) can be seen
        $("#firstlevel").on('click', function() {
            <?php for ($i = 0; $i < count($json); $i++) {?>
                <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
                    var currentjson = <?php echo $json[$i][$j] ?>;
                    gantt.close(currentjson.id);
                <?php } ?>
            <?php } ?>
        });

        $("#zoomout").on('click', function() {
            zoomModule.zoomOut();
        });

        $("#zoomin").on('click', function() {
            zoomModule.zoomIn();
        });

        //exports the information on the left side of the chart into an excel document
        $("#export").on('click', function() {
            var c = window.confirm("Would you like to export the Gantt Chart data to an Excel file?");
            if(c == true){
                gantt.exportToExcel(project);
            }
        });

    });

</script>

</body>