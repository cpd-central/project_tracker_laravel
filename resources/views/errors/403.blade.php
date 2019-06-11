@extends('errors::minimal')

@section('page-title', '403')
@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
