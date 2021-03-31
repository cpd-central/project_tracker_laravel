<?php

  use App\User;
  /**
   * Checks if array $typeArray is not null and then checks to see if $type is in $typeArray.
   * @param $type - variable to be checked if its in $typeArray. 
   * @param $typeArray - array that contains keywords of boxes that are checked.
   * @return "checked"
   */
  function check_project_box($type, $typeArray) {
    if(isset($typeArray)) {
      if(in_array($type, $typeArray)) {
        echo 'checked';
      }
    }
  }

  /**
   * Checks if $saved_bill is not null and then checks to see if the $type keyword matches $saved_bill.
   * @param $type - variable to be checked if it matches $saved_bill. 
   * @param $saved_bill - Billing method keyword of radio button that is checked.
   * @return "checked"
   */
  function check_billing_method2($type, $saved_bill) {
    if(isset($saved_bill)) {
      if($type == $saved_bill) {
        return 'checked';
      }
    }
  }
?>
@extends('layouts.input')

@section('page-title', 'Edit Project')
@section('title', 'Project Update Form')
@section('h4proposal', 'Edit Project - Proposal Details')
@section('h4won', 'Edit Project - Won Details')

<?php $authors = User::all()->except('role', 'user')?>
@section('cegproposalauthor')
@foreach($authors as $author)
<?php if ($project['cegproposalauthor'] == $author->name){?>
  <option value="<?=$author->name?>" selected="selected"><?=$author->name?></option>
<?php } else { ?>
  <option value="<?=$author->name?>"><?=$author->name?></option>
<?php } ?>
@endforeach
@stop

@section('projectname', $project['projectname'])
@section('clientcontactname', $project['clientcontactname'])
@section('state', $project['state'])
@section('utility', $project['utility'])
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
  <option>Probable</option>
  <option>Expired</option>
  <option>Done and Billing Complete</option>
@elseif ($project['projectstatus'] == 'Expired')
  <option>Proposed</option>
  <option>Won</option>
  <option>Probable</option>
  <option selected="selected">Expired</option>
  <option>Done and Billing Complete</option>
@elseif ($project['projectstatus'] == 'Probable')
  <option>Proposed</option>
  <option>Won</option>
  <option selected="selected">Probable</option>
  <option>Expired</option>
  <option>Done and Billing Complete</option>
@elseif ($project['projectstatus'] == 'Done and Billing Complete')
  <option>Proposed</option>
  <option>Won</option>
  <option>Probable</option>
  <option>Expired</option>
  <option selected="selected">Done and Billing Complete</option>
@else
  <option selected="selected">Proposed</option>
  <option>Won</option>
  <option>Probable</option>
  <option>Expired</option>
  <option>Done and Billing Complete</option>
@endif
@stop

@section('projectcode', $project['projectcode'])

<?php $pms = User::where('role', 'proposer')->orWhere('role', 'admin')->get()?>
@section('projectmanager')
<?php if ($project['projectmanager'][0] == "" || $project['projectmanager'][0] == null){?>
  <option value="" selected="selected">No Project Manager</option>
  <?php } else { ?>
    <option value="">No Project Manager</option>
<?php } ?>
@foreach($pms as $pm)
<?php if ($project['projectmanager'][0] == $pm->name){?>
  <option value="<?=$pm->name?>" selected="selected"><?=$pm->name?></option>
<?php } else { ?>
  <option value="<?=$pm->name?>"><?=$pm->name?></option>
<?php } ?>
@endforeach
@stop

@section('projectnotes', $project['projectnotes'])

@section('billingcontact', $project['billingcontact'])
@section('billingcontactemail', $project['billingcontactemail'])
@section('billingnotes', $project['billingnotes'])
@section('TandM', check_billing_method2('TandM',$project['billingmethod']))
@section('Lump', check_billing_method2('Lump',$project['billingmethod']))
@section('SOV', check_billing_method2('SOV',$project['billingmethod']))
@section('filelocationofproposal', $project['filelocationofproposal'])
@section('filelocationofproject', $project['filelocationofproject'])


