<!DOCTYPE html>


<?php
//dd($json);
?>

<head>
    @include('includes.navbar')
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
 
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
<div id="gantt_here" style='width:100%; height:100%;'></div>

<script type="text/javascript">
    //gantt.config.readonly = true;
    //gantt.config.start_date = new Date(2020, 05, 10);
    //gantt.config.end_date = new Date(2020, 11, 31);
    gantt.config.xml_date = "%Y-%m-%d";
    gantt.config.scales = [
    {unit: "month", step: 1, format: "%F, %Y"},
    {unit: "day", step: 1, format: "%j, %D"}
    ];
    
    var data = [];
    <?php for ($i = 0; $i < count($json); $i++) {?>
        data.push(<?php echo $json[$i]?>);
    <?php } ?>
    var project = {};
    project={"data": data};

    console.log(project);
    

    gantt.init("gantt_here");
    gantt.parse(project);
</script>
</body>