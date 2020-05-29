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
