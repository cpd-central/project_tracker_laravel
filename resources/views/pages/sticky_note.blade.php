<!DOCTYPE html>

<?php //dd($json); ?>

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
<div id="gantt_here" style='width:100%; height:75%;'></div>

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

    gantt.config.columns =  [
    {name:"text",       label:"Project name",  tree:true, width: 250 },
    {name:"end_date",   label:"End date",   align:"center", width: 75 },
    {name:"name_1",   label:"Employee 1",   align:"center", width: 100 },
    {name:"name_2",   label:"Employee 2",   align:"center", width: 100 },
    {name:"add",        label:"",           width:30 }
    ];
    
    
    gantt.config.xml_date = "%Y-%m-%d";
    
    gantt.config.scales = [
    {unit: "month", step: 1, format: "%F, %Y"},
    {unit: "day", step: 1, format: "%j, %D"}
    ];
    
    var data = [];
    <?php for ($i = 0; $i < count($json); $i++) {?>
        <?php for ($j = 0; $j < count($json[$i]); $j++) {?>
            data.push(<?php echo $json[$i][$j]?>);
        <?php } ?>
     <?php } ?>
    var project = {};
    project={"data": data};
    
    gantt.init("gantt_here");
    //gantt.config.placeholder_task = true;
    gantt.parse(project);
</script>
</body>