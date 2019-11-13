@extends('layouts.index')
@section('toptool')
<h2><b>Past Year Dollars Per Month</b></h2>
<div>
  @isset($chart) 
  {!! $chart->container() !!} 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
  {!! $chart->script() !!}
  @endisset
</div>
@stop 