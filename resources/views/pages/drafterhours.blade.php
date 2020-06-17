<!DOCTYPE html>
@extends('layouts.default')

@section('page-title', 'Drafter Hours')
@section('content')
    @isset($charts)
    @foreach($charts as $chart) 
        <div class="htmlgraphhours">
            {!! $chart->container() !!} 
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" chartset="utf-8"></script>
            {!! $chart->script() !!}
        </div>
    <table class="table table-striped">
        <tr>
            <td></td>
            <td style="text-align:right"><b>% Billiable: </b></td>
            <td style="text-align:left">{{$chart->options['percent_billable']."%"}}</td>
            <td></td>
            <td style="text-align:left"><b>Project Count: </b></td>
            <td style="text-align:left">{{$chart->options['projectcount']}}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td><b>Total Hours Non-Billable</b></td>
            <td><b>CEG: </b></td>
            <td>@if (isset($chart->options['CEG'])){{$chart->options['CEG']}} @else{{0}}@endif</td>
            <td><b>CEGTRNG: </b></td>
            <td>@if (isset($chart->options['CEGTRNG'])){{$chart->options['CEGTRNG']}} @else{{0}}@endif</td>
            <td><b>CEGMKTG: </b></td>
            <td>@if (isset($chart->options['CEGMKTG'])){{$chart->options['CEGMKTG']}} @else{{0}}@endif</td>
            <td><b>CEGEDU: </b></td>
            <td>@if (isset($chart->options['CEGEDU'])){{$chart->options['CEGEDU']}} @else{{0}}@endif</td>
        </tr>
    </table>
    @endforeach
    @endisset
@stop


