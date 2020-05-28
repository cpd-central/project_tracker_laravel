@extends('layouts.default')
@section('page-title', 'Manage Project')

@section('content')
<?php
echo "{$project['projectname']} {$project['cegproposalauthor']}";
?>
@stop