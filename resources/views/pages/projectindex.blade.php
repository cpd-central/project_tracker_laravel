@extends('layouts.default')
@section('content')

<div class="container">
  <br />
  @if (\Session::has('success'))
  <div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
  </div><br />
  @endif
  <table class="table table-striped">
    <thead>
      <tr>
        <th colspan="3">Action</th>
        <th>CEG Proposal Author</th>
        <th>Project Name</th>
        <th>Client Contact</th>
        <th>Client Company</th>
        <th>MW</th>
        <!-- <th>voltage</th> -->
        <th>CEG In-house Budget</th>
        <!-- <th>dateproposed</th>  -->
        <th>Date NTP</th>
        <th>Date Energize</th>   

        <!-- <th>projectrypewind</th>
          <th>projectypesolar</th>
          <th>projecttypestorage</th>
          <th>projecttypearray</th>
          <th>projecttypetransmission</th>
          <th>projecttypesubstation</th>
          <th>projecttypedistribution</th>
          <th>projectypescada</th>
          <th>projectypestudy</th>  

          <th>electricalengineering</th>
          <th>civilengineering</th>
          <th>structuralmechanicalengineering</th>  
          <th>procurement</th>
          <th>construction</th> --> 
          <th>Bid/Won</th>   
          <th>Project Code</th>
          <th>Project Manager</th>


      </tr>
    </thead>
    <tbody>

      @foreach($projects as $project)
      <tr>
        <td><a href="{{action('newprojectcontroller@new_project', $project['_id'])}}" class="btn btn-primary">New</a></td>
        <td><a href="{{action('newprojectcontroller@edit_project', $project['_id'])}}" class="btn btn-warning">Edit</a></td>
        <td>
          <form action="{{action('newprojectcontroller@destroy', $project['id'])}}" method="post">
            @csrf
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>

        <td>{{ $project['cegproposalauthor'] }}</td>
        <td>{{ $project['projectname']}}</td >        
        <td>{{ $project['clientcontactname'] }}</td>
        <td>{{ $project['clientcompany'] }}</td>
        <td>{{ $project['mwsize'] }}</td>
        <td>{{ $project['voltage'] }}</td>
        <td>{{ $project['dollarvalueinhouse'] }}</td>
        <td>{{ $project['dateproposed'] }}</td>
        <td>{{ $project['datentp'] }}</td>
        <td>{{ $project['dateenergization'] }}</td>
        <td>{{ $project['sel1']}}</td > 
        <td>{{ $project['projectcode'] }}</td>
        <td>{{ $project['projectmanager'] }}</td>


      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@stop















