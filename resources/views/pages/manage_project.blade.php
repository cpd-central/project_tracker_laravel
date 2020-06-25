@extends('layouts.manage')

@section('page-title', 'Manage '. $project['projectname'])
@section('title', 'Manage Project Form')
@section('h4proposal', 'Edit Project Due Dates')
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

@if(isset($project['duedates']['studies']))
  @section('studiesperson1', $project['duedates']['studies']['person1'])
  @section('studiesdue', $project['duedates']['studies']['due'])
  <?php $keys = array_keys($project['duedates']['studies']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "due"){ ?>
      @section('study'.$i.'person1', $project['duedates']['studies'][$key]['person1'])
      @section('study'.$i.'due', $project['duedates']['studies'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['scada']))
  @section('scadaperson1', $project['duedates']['scada']['person1'])
  @section('scadaperson2', $project['duedates']['scada']['person2'])
  @section('scadadue', $project['duedates']['scada']['due'])
  <?php $keys = array_keys($project['duedates']['scada']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('scada'.$i.'person1', $project['duedates']['scada'][$key]['person1'])
      @section('scada'.$i.'person2', $project['duedates']['scada'][$key]['person2'])
      @section('scada'.$i.'due', $project['duedates']['scada'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['rtac']))
  @section('rtacperson1', $project['duedates']['rtac']['person1'])
  @section('rtacperson2', $project['duedates']['rtac']['person2'])
  @section('rtacdue', $project['duedates']['rtac']['due'])
  <?php $keys = array_keys($project['duedates']['rtac']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('rtac'.$i.'person1', $project['duedates']['rtac'][$key]['person1'])
      @section('rtac'.$i.'person2', $project['duedates']['rtac'][$key]['person2'])
      @section('rtac'.$i.'due', $project['duedates']['rtac'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['communication']))
  @section('communicationperson1', $project['duedates']['communication']['person1'])
  @section('communicationperson2', $project['duedates']['communication']['person2'])
  @section('communicationdue', $project['duedates']['communication']['due'])
  <?php $keys = array_keys($project['duedates']['communication']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('communication'.$i.'person1', $project['duedates']['communication'][$key]['person1'])
      @section('communication'.$i.'person2', $project['duedates']['communication'][$key]['person2'])
      @section('communication'.$i.'due', $project['duedates']['communication'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['fieldwork']))
  @section('fieldworkperson1', $project['duedates']['fieldwork']['person1'])
  @section('fieldworkperson2', $project['duedates']['fieldwork']['person2'])
  @section('fieldworkdue', $project['duedates']['fieldwork']['due'])
  <?php $keys = array_keys($project['duedates']['fieldwork']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('fieldwork'.$i.'person1', $project['duedates']['fieldwork'][$key]['person1'])
      @section('fieldwork'.$i.'person2', $project['duedates']['fieldwork'][$key]['person2'])
      @section('fieldwork'.$i.'due', $project['duedates']['fieldwork'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['transmission']))
  @section('transmissionperson1', $project['duedates']['transmission']['person1'])
  @section('transmissionperson2', $project['duedates']['transmission']['person2'])
  @section('transmissiondue', $project['duedates']['transmission']['due'])
  <?php $keys = array_keys($project['duedates']['transmission']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('transmission'.$i.'person1', $project['duedates']['transmission'][$key]['person1'])
      @section('transmission'.$i.'person2', $project['duedates']['transmission'][$key]['person2'])
      @section('transmission'.$i.'due', $project['duedates']['transmission'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['collection']))
  @section('collectionperson1', $project['duedates']['collection']['person1'])
  @section('collectionperson2', $project['duedates']['collection']['person2'])
  @section('collectiondue', $project['duedates']['collection']['due'])
  <?php $keys = array_keys($project['duedates']['collection']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('collection'.$i.'person1', $project['duedates']['collection'][$key]['person1'])
      @section('collection'.$i.'person2', $project['duedates']['collection'][$key]['person2'])
      @section('collection'.$i.'due', $project['duedates']['collection'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['control']))
  @section('controlperson1', $project['duedates']['control']['person1'])
  @section('controlperson2', $project['duedates']['control']['person2'])
  @section('controldue', $project['duedates']['control']['due'])
  <?php $keys = array_keys($project['duedates']['control']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('control'.$i.'person1', $project['duedates']['control'][$key]['person1'])
      @section('control'.$i.'person2', $project['duedates']['control'][$key]['person2'])
      @section('control'.$i.'due', $project['duedates']['control'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@if(isset($project['duedates']['physical']))
  @section('physicalperson1', $project['duedates']['physical']['person1'])
  @section('physicalperson2', $project['duedates']['physical']['person2'])
  @section('physicaldue', $project['duedates']['physical']['due'])
  <?php $keys = array_keys($project['duedates']['physical']); ?>
  <?php $i = 1; ?>
  @foreach($keys as $key)
    <?php if ($key != "person1" && $key != "person2" && $key != "due"){ ?>
      @section('physical'.$i.'person1', $project['duedates']['physical'][$key]['person1'])
      @section('physical'.$i.'person2', $project['duedates']['physical'][$key]['person2'])
      @section('physical'.$i.'due', $project['duedates']['physical'][$key]['due'])
      <?php $i++; ?>
    <?php } ?>
  @endforeach
@endif
@section('dynamic_field')

