<!-- newprojectindex.blade.php -->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Complete Project List</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
  </head>
  <body>
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
      
      @foreach($newprojects as $newproject)
      <tr>
        <td><a href="{{action('newprojectcontroller@create', $newproject->id)}}" class="btn btn-primary">New</a></td>
        <td><a href="{{action('newprojectcontroller@edit', $newproject->id)}}" class="btn btn-warning">Edit</a></td>
        <td>
          <form action="{{action('newprojectcontroller@destroy', $newproject->id)}}" method="post">
            @csrf
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </td>

        <td>{{$newproject->cegproposalauthor}}</td>
        <td>{{$newproject->projectname}}</td>        
        <td>{{$newproject->clientcontactname}}</td>
        <td>{{$newproject->clientcompany}}</td>
        <td>{{$newproject->mwsize}}</td>
        <!-- <td>{{$newproject->voltage}}</td>    -->
        <td>{{$newproject->dollarvalueinhouse}}</td>
        <!-- <td>{{$newproject->dateproposed}}</td>   -->
        <td>{{$newproject->datentp}}</td>
        <td>{{$newproject->dateenergization}}</td>

        <!-- <td>{{$newproject->projectrypewind}}</td>
        <td>{{$newproject->projectypesolar}}</td>
        <td>{{$newproject->projecttypestorage}}</td>
        <td>{{$newproject->projecttypearray}}</td>
        <td>{{$newproject->projecttypetransmission}}</td>
        <td>{{$newproject->projecttypesubstation}}</td>
        <td>{{$newproject->projecttypedistribution}}</td>
        <td>{{$newproject->projectypescada}}</td>
        <td>{{$newproject->projectypestudy}}</td>

        <td>{{$newproject->electricalengineering}}</td>
        <td>{{$newproject->civilengineering}}</td>
        <td>{{$newproject->structuralmechanicalengineering}}</td>
        <td>{{$newproject->procurement}}</td>
        <td>{{$newproject->construction}}</td>  -->
        <td>{{$newproject->sel1}}</td> 
        <td>{{$newproject->projectcode}}</td>
        <td>{{$newproject->projectmanager}}</td>


      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  </body>
</html>
