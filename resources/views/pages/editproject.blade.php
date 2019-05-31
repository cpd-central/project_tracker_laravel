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

@section('projectstatus')
@if ($project['projectstatus'] == 'Won') 
  <option>Proposed</option>
  <option selected="selected">Won</option>
  <option>Expired</option>
@elseif ($project['projectstatus'] == 'Expired')
  <option>Proposed</option>
  <option>Won</option>
  <option selected="selected">Expired</option>
@else
  <option selected="selected">Proposed</option>
  <option>Won</option>
  <option>Expired</option>
@endif
@stop

@section('projectcode', $project['projectcode'])
@section('projectmanager', $project['projectmanager'])


