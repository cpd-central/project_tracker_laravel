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
@section('dollarvalueinhouse', $project['dollarvalueinhouse'])
@section('dateproposed', $project['dateproposed'])
@section('datentp', $project['datentp'])
@section('dateenergization', $project['dateenergization'])

<!-- Project Type Sections -->
@section('projecttypewind')
<?php
  check_project_box('Wind', $project['projecttype']);
?>
@stop
@section('projecttypesolar')
<?php
  check_project_box('Solar', $project['projecttype']);
?>
@stop
@section('projecttypestorage')
<?php
  check_project_box('Storage', $project['projecttype']);
?>
@stop
@section('projecttypearray')
<?php
  check_project_box('Array', $project['projecttype']);
?>
@stop
@section('projecttypetransmission')
<?php
  check_project_box('Transmission', $project['projecttype']);
?>
@stop
@section('projecttypesubstation')
<?php
  check_project_box('Substation', $project['projecttype']);
?>
@stop
@section('projecttypedistribution')
<?php
  check_project_box('Distribution', $project['projecttype']);
?>
@stop
@section('projecttypescada')
<?php
  check_project_box('SCADA', $project['projecttype']);
?>
@stop
@section('projecttypestudy')
<?php
  check_project_box('Study', $project['projecttype']);
?>
@stop
<!-------------------------------------------------------->
<!-- EPC Type Sections -->
@section('epctypeelectricalengineering')
<?php
  check_project_box('Electrical Engineering', $project['epctype']);
?>
@stop
@section('epctypecivilengineering')
<?php
  check_project_box('Civil Engineering', $project['epctype']);
?>
@stop
@section('epctypestructuralmechanicalengineering')
<?php
  check_project_box('Structural/Mechanical Engineering', $project['epctype']);
?>
@stop
@section('epctypeprocurement')
<?php
  check_project_box('Procurement', $project['epctype']);
?>
@stop
@section('epctypeconstruction')
<?php
  check_project_box('Construction', $project['epctype']);
?>
@stop
<!-------------------------------------------------------->

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



