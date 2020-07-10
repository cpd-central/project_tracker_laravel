<!DOCTYPE html>

<?php //dd($json); ?>
<head>
    @include('includes.navbar')
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
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
<div id="gantt_here" style='width:100%; height:75%;'></div>
<br>
<button type="button" class="btn btn-primary" id="thirdlevel">Expand All</button>
<button style="margin:10px;" type="button" class="btn btn-primary" id="secondlevel">Expand One Level</button>
<button type="button" class="btn btn-primary" id="firstlevel">Contract All</button>
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
    {name:"text",       label:"Project name",  tree:true, width: 250 },
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
    });

</script>

</body>