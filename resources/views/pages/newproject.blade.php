<?php
  use App\User; 
?>
@extends('layouts.input')

@section('page-title', 'New Project')
@section('title', 'Project Insert Form')
@section('h4proposal', 'New Project - Proposal Details')
@section('h4won', 'New Project - Won Details')

<?php $authors = User::all()->except('role', 'user')?>
@section('cegproposalauthor')
<option value="<?=auth()->user()->name?>"><?=auth()->user()->name?></option>
@foreach($authors as $author)
  <?php if($author->name != auth()->user()->name){ ?>
  <option value="<?=$author->name?>"><?=$author->name?></option>
  <?php } ?>
@endforeach
@stop

@section('projectname', '')
@section('clientcompany', '')
@section('state', '')
@section('utility', '')
@section('clientcontactname', '')
@section('mwsize', '')
@section('voltage', '')
@section('dollaravlueinhouse', '')
@section('dateproposed', '')
@section('datentp', '')
@section('dateenergization', '')
@section('dateenergization', '')

@section('projectstatus')
  <option>Proposed</option>
  <option>Won</option>
  <option>Probable</option>
  <option>Expired</option>
  <option>Done and Billing Complete</option>
@stop

@section('projectcode', '')

<?php $pms = User::where('role', 'proposer')->orWhere('role', 'admin')->get()?>
@section('projectmanager')
<option value="">No Project Manager</option>
@foreach($pms as $pm)
  <option value="<?=$pm->name?>"><?=$pm->name?></option>
@endforeach
@stop

@section('projectnotes', '')

@section('billingcontact', '')
@section('billingcontactemail', '')
@section('billingnotes', '')
@section('filelocationofproposal', '')
@section('filelocationofproject', '')



