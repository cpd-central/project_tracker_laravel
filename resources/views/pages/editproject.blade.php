@extends('layouts.input')

@section('title', 'Project Update Form')
@section('h4proposal', 'Edit Project - Proposal Details')
@section('h4won', 'Edit Project - Won Details')

@section('cegproposalauthor', $project['cegproposalauthor'])
@section('projectname', $project['projectname'])
@section('clientcontactname', $project['clientcontactname'])
@section('clientcompany', $project['clientcompany'])
@section('mwsize', $project['mwsize'])
@section('voltage', $project['voltage'])
@section('dollaravlueinhouse', $project['dollaravlueinhouse'])
@section('dateproposed', $project['dateproposed'])
@section('datentp', $project['datentp'])
@section('dateenergization', $project['dateenergization'])
@section('dateenergization', $project['dateenergization'])

@section('checkboxes')

@section('projecttypewind')
<?php
  if(isset($project['projecttype']))
  {
    if(in_array('Wind', $project['projecttype'])){
      echo 'checked';
    }
  }
?>
@stop
@section('projecttypesolar')
<?php
  if(isset($project['projecttype']))
  { 
    if(in_array('Solar', $project['projecttype'])){
      echo 'checked';
    }
  }
?>
@stop
@section('projecttypestorage')
<?php
  if(isset($project['projecttype']))
  { 
    if(in_array('Storage', $project['projecttype'])){
      echo 'checked';
    }
  }
?>
@stop


@section('projectwon', $project['projectwon'])
@section('projectcode', $project['projectcode'])
@section('projectmanager', $project['projectmanager'])



