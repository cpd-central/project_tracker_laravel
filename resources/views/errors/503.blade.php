@extends('errors::minimal')

@section('page-title', 'Error 503')
@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))
