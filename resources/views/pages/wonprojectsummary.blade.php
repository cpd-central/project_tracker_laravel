@extends('layouts.default')
@section('content')

<div class="container">
  <h2><b>Project Search</b></h2> 
  <br />
  <!-- Search Bar Form -->
  <div class="active-pink-3 active-pink-4 mb-4">
    <form class="form-inline md-form mr-auto mb-4" method="post" action="{{action('ProjectController@search')}}"> 
      @csrf 
      <input name="search" class="form-control mr-sm-2" type="text" placeholder="Search Projects" aria-label="Search">
      <button class="btn aqua-gradient btn-rounded btn-sm my-0" type="submit">Search</button> 
    </form> 
  </div>

  <br />
  @if (\Session::has('success'))
  <div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
  </div><br />
  @endif
  <h2><b>Project Index</b></h2> 
  <table class="table table-striped">
    <thead>
      <tr>
        <th colspan="2">Action</th>
        <th>Project Name</th>
        <th>Dollar Value</th>
        <th colspan="2">Date NTP</th>
        <th colspan="2">Date Energization</th>
        <?php 
        $x=5;
        $y=0;
        $z=0;
        $FIELDS=5;
        #$today=date("M-y-d");
        #echo "<br>" . "today: " . strtotime(date("M-y", strtotime($today))) . "+1 month";

		#$date = date("Y-m-d");

		#$date = strtotime(date("Y-m-d", strtotime($date)) . " +1 month");
		#$date = date("Y-m-d",$date);
		#echo "<br>" . "Date: " . $date;
        #$MAXxDATE = "{$MAXxDATE}";
        #echo "<br>" . "MAXxDATE: " . ($MAXxDATE);
        #echo "<br>" . "MAXxDATE: " . (int)($MAXxDATE);
        #echo "<br>" . "typeMAXxDATE: " . gettype(("{{$MAXxDATE}}"));
        #$MAXxDATE_FIELD_count = (int)"{{$MAXxDATE}}" + $FIELDS;
        #echo "<br>" . "MAXxDATE_FIELD_count" . gettype((int)"{{$MAXxDATE_FIELD_count}}");
        #echo "<br>" . "MAXxDATE: " . (int)"{{$MAXxDATE}}";
        #echo "<br>" . "FIELDS: " . $FIELDS;
        #echo "<br>" . "MAXxDATE_FIELD_count: " . (int)$MAXxDATE_FIELD_count;
        for ($x;$x<("{$MAXxDATE}"+$FIELDS);$x++)
        {
        	$projectWONarray[$x][$y]="" . date("M-y",((int)"{$today}"+2629743*($z+1)));
	        echo "<th colspan='1'>" . $projectWONarray[$x][$y] .  "</th>";
	        $z++;
        };
        $x=0;
        echo "</tr>";
        echo "<br>";
        ?>
        <th>Mon1</th>
        <th>Mon2</th>
        <th>Mon3</th>
        <th>Mon4</th>   
        <th>Mon5</th>
        <th>Mon6</th>
        <th>Mon7</th>   
        <th>Mon8</th>
        <th>Mon9</th>
		<th>Mon10</th>
		<th>Mon11</th>
        <th>Mon12</th>
        <th>Mon13</th>
        <th>Mon14</th>   
        <th>Mon15</th>
        <th>Mon16</th>
        <th>Mon17</th>   
        <th>Mon18</th>
        <th>Mon19</th>
		<th>Mon20</th>
		<th>Mon21</th>
		<th>Mon22</th>
		<th>Mon23</th>
		<th>Mon24</th>
      </tr>
    </thead>
    <tbody>

      @foreach($projects as $project)
      <tr>
        <td><a href="{{action('ProjectController@edit_project', $project['_id'])}}" class="btn btn-warning">Edit</a></td>
        <td>
          <form action="{{action('ProjectController@destroy', $project['id'])}}" method="post">
            @csrf
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit" onclick="return confirm('This will delete the project from the database.  Are you sure you want to do this?')">Delete</button>
          </form>
        </td>

        <td>{{ $project['projectname']}}</td >        
        <td>{{ $project['dollarvalueinhouse'] }}</td>
        <td colspan="2">{{ $project['datentp'] }}</td>
        <td colspan="2">{{ $project['dateenergization'] }}</td>

       	<td>Mon1</td>
        <td>Mon2</td>
        <td>Mon3</td>
        <td>Mon4</td>   
        <td>Mon5</td>
        <td>Mon6</td>
        <td>Mon7</td>   
        <td>Mon8</td>
        <td>Mon9</td>


      </tr>
      @endforeach
      <tr>
        <td>Total</td>
        <td>Total</td>

        <td>Total</td >        
        <td>Total</td>
        <td>Total</td>
        <td>Total</td>

       	<td>Total</td>
        <td>Total</td>
        <td>Total</td>
        <td>Total</td>   
        <td>Total</td>
        <td>Total</td>
        <td>Total</td>   
        <td>Total</td>
        <td>Total</td>


      </tr>
      </tbody>
  </table>
</div>

@stop

























