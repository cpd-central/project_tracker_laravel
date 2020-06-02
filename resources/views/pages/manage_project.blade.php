@extends('layouts.manage')
@section('page-title', 'Manage Project')
@section('title', 'Manage Project Form')
@section('h4proposal', 'Edit Project Plans')
@section('projectname', $project['projectname'])
@section('projectmanager')
<?php $projectmanagers = "";
        if($project['projectmanager'] != null){
          if(is_array($project['projectmanager'])){
            for($i=1; $i <= count($project['projectmanager']); $i++){
              if($i == count($project['projectmanager'])){
                $projectmanagers = $projectmanagers.$project['projectmanager'][$i-1];
              }
              else{
                $projectmanagers = $projectmanagers.$project['projectmanager'][$i-1].', ';
              }
            } 
          }
          else{
            $projectmanagers = $project['projectmanager'];
          }
        }
        echo $projectmanagers?>
@stop
@section('dateenergization', $project['dateenergization'])

@section('physicaldrafter90', $project['duedates']['physical']['ninety']['drafter'])
@section('physicaleng90', $project['duedates']['physical']['ninety']['engineer'])
@section('physicaldate90', $project['duedates']['physical']['ninety']['due'])
@section('physicaldrafterifc', $project['duedates']['physical']['ifc']['drafter'])
@section('physicalengifc', $project['duedates']['physical']['ifc']['engineer'])
@section('physicaldateifc', $project['duedates']['physical']['ifc']['due'])

@section('wiredrafter90', $project['duedates']['wiring']['ninety']['drafter'])
@section('wireeng90', $project['duedates']['wiring']['ninety']['engineer'])
@section('wiredate90', $project['duedates']['wiring']['ninety']['due'])
@section('wiredrafterifc', $project['duedates']['wiring']['ifc']['drafter'])
@section('wireengifc', $project['duedates']['wiring']['ifc']['engineer'])
@section('wiredateifc', $project['duedates']['wiring']['ifc']['due'])

@section('collectiondrafter90', $project['duedates']['collection']['ninety']['drafter'])
@section('collectioneng90', $project['duedates']['collection']['ninety']['engineer'])
@section('collectiondate90', $project['duedates']['collection']['ninety']['due'])
@section('collectiondrafterifc', $project['duedates']['collection']['ifc']['drafter'])
@section('collectionengifc', $project['duedates']['collection']['ifc']['engineer'])
@section('collectiondateifc', $project['duedates']['collection']['ifc']['due'])

@section('transmissiondrafter90', $project['duedates']['transmission']['ninety']['drafter'])
@section('transmissioneng90', $project['duedates']['transmission']['ninety']['engineer'])
@section('transmissiondate90', $project['duedates']['transmission']['ninety']['due'])
@section('transmissiondrafterifc', $project['duedates']['transmission']['ifc']['drafter'])
@section('transmissionengifc', $project['duedates']['transmission']['ifc']['engineer'])
@section('transmissiondateifc', $project['duedates']['transmission']['ifc']['due'])

@section('scadadrafter', $project['duedates']['scada']['drafter'])
@section('scadaeng', $project['duedates']['scada']['engineer'])
@section('scadadate', $project['duedates']['scada']['due'])

@section('reactiveeng', $project['duedates']['reactive']['engineer'])
@section('reactivedate', $project['duedates']['reactive']['due'])

@section('ampacityeng', $project['duedates']['ampacity']['engineer'])
@section('ampacitydate', $project['duedates']['ampacity']['due'])

@section('arcflasheng', $project['duedates']['arcflash']['engineer'])
@section('arcflashdate', $project['duedates']['arcflash']['due'])

@section('relayeng', $project['duedates']['relay']['engineer'])
@section('relaydate', $project['duedates']['relay']['due'])

@section('alleng', $project['duedates']['allothers']['engineer'])
@section('alldate', $project['duedates']['allothers']['due'])
