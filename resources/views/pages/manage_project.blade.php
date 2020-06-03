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

@section('physical90person1', $project['duedates']['physical90']['person1'])
@section('physical90person2', $project['duedates']['physical90']['person2'])
@section('physical90due', $project['duedates']['physical90']['due'])

@section('physicalifcperson1', $project['duedates']['physicalifc']['person1'])
@section('physicalifcperson2', $project['duedates']['physicalifc']['person2'])
@section('physicalifcdue', $project['duedates']['physicalifc']['due'])

@section('wire90person1', $project['duedates']['wiring90']['person1'])
@section('wire90person2', $project['duedates']['wiring90']['person2'])
@section('wire90due', $project['duedates']['wiring90']['due'])

@section('wireifcperson1', $project['duedates']['wiringifc']['person1'])
@section('wireifcperson2', $project['duedates']['wiringifc']['person2'])
@section('wireifcdue', $project['duedates']['wiringifc']['due'])

@section('collection90person1', $project['duedates']['collection90']['person1'])
@section('collection90person2', $project['duedates']['collection90']['person2'])
@section('collection90due', $project['duedates']['collection90']['due'])

@section('collectionifcperson1', $project['duedates']['collectionifc']['person1'])
@section('collectionifcperson2', $project['duedates']['collectionifc']['person2'])
@section('collectionifcdue', $project['duedates']['collectionifc']['due'])

@section('transmission90person1', $project['duedates']['transmission90']['person1'])
@section('transmission90person2', $project['duedates']['transmission90']['person2'])
@section('transmission90due', $project['duedates']['transmission90']['due'])

@section('transmissionifcperson1', $project['duedates']['transmissionifc']['person1'])
@section('transmissionifcperson2', $project['duedates']['transmissionifc']['person2'])
@section('transmissionifcdue', $project['duedates']['transmissionifc']['due'])

@section('scadaperson1', $project['duedates']['scada']['person1'])
@section('scadaperson2', $project['duedates']['scada']['person2'])
@section('scadadue', $project['duedates']['scada']['due'])

@section('reactiveperson1', $project['duedates']['reactive']['person1'])
@section('reactivedue', $project['duedates']['reactive']['due'])

@section('ampacityperson1', $project['duedates']['ampacity']['person1'])
@section('ampacitydue', $project['duedates']['ampacity']['due'])

@section('arcflashperson1', $project['duedates']['arcflash']['person1'])
@section('arcflashdue', $project['duedates']['arcflash']['due'])

@section('relayperson1', $project['duedates']['relay']['person1'])
@section('relaydue', $project['duedates']['relay']['due'])

@section('allperson1', $project['duedates']['allothers']['person1'])
@section('alldue', $project['duedates']['allothers']['due'])
