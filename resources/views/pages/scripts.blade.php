@extends('layouts.index')

@section('page-title', 'Scripts')

@section('table-content')
<form class="form-inline md-form mr-auto mb-4" method="post" action="/scriptexecute"> 
          @csrf 
          <button class="btn btn-success" type="submit">Execute</button>           
</form> 
@endsection