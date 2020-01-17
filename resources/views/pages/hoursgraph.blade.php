<!DOCTYPE html>
@extends('layouts.default')

@section('page-title', 'Hours By Project Graph')
@section('content')
{{ old('project_id') }}
<?php $x=0; ?>
<div class="container">
  </br>
  </br>
  <div class="row justify-content-center">
    <div class="card">
      <div class="card-body">
        <form method="get" id="projectcode-form">
          <!--<label for="project-content">Select Project</label>!-->
          <select name="project_id" class="form-control" onchange="document.getElementById('projectcode-form').submit()">
            <option value="0">-----------Select Project Code-----------</option>
            @foreach($projects as $project)
            <option value="{{ $project['_id'] }}">{{ $project['projectname'] }}</option>
            @endforeach
          </select>
        </form>
      </div>
    </div>
  </div>
</div>
@foreach($chart_variable as $var2)
<?php 
    $chart_variable[$x]=$var2;
    $x++;
    ?>
@endforeach


<?php 
    $x=0;
    ?>




<form action="{{ route('pages.hoursgraph') }}" method="POST">
  @csrf
  @isset($chart_units)
  @if ($chart_units == 'hours')
  <!-- <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="dollars"><button class="btn btn-primary" name="button" type="submit" value="dollars">Toggle Hours/Dollars</button> -->

    @foreach($chart_dollars as $var)
    <div class="sheepy">
      @isset($var)
      {!! $var->container() !!}
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
      {!! $var->script() !!}
      @endisset
    </div>





    <div class="sheepy2">
      <table class="table table-striped">
        <thead>
          <tr>



            <td colspan="1">Total Spent:</td>
            <td colspan="1"><?php echo $var->options["CEGtimespenttodate"] . "-hr";  ?></td>

            <td colspan="1"><?php echo "$" . $var->options["total_project_dollars"];  ?></td>



            <td colspan="2">In House Budget:</td>
            <td colspan="3"><?php echo "$" . $var->options["dollarvalueinhouse"];  ?></td>

            <td colspan="4">




            </td>

            <td colspan="3">
              <div class="form-group col-md-4">
                <input type="<?php echo $var->options["title"]["text"]; ?>text" value="1000000">
              </div>
            </td>



          </tr>
          <tr>






            <td colspan="1">Previous Month:</td>
            <td colspan="1"><?php echo $var->options["previous_month_project_hours"] . "-hr";  ?></td>

            <td colspan="1"><?php echo "$" . $var->options["previous_month_project_monies"];  ?></td>

            <td colspan="2">Energization Date:</td>
            <td colspan="3"><?php echo $var->options["dateenergization"];  ?></td>
            <td colspan="4">



            </td>

            <td colspan="1">CEG billed $XXXXXX on XXXX-XX-XX</td>
              


              <div class="form-group col-md-4">
              <label for="cegproposalauther">Bill This Amount</label>
              <?php $id = $var->options['id']; ?> 

              <input class="btn btn-primary" name="<?php echo $x; ?>_ID" type="hidden" value=<?php echo $id; ?>>
               <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="hours"><button href="{{action('ProjectController@blah', $id)}}" class="btn btn-primary" method="POST" type="submit">Edit</button> 

              <!-- <a href="{{action('ProjectController@blah', $project['_id'])}}" class="btn btn-warning">Edit</a> -->



               <input name="textblock1" type="text" value="0">



              </div>


          </tr>
        </thead>
      </table>
    </div>

    <?php $x++; ?>

    @endforeach






  @elseif ($chart_units == 'dollars')
  <tr>
    <td>
      <!-- <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="hours"><button class="btn btn-primary" name="button" type="submit" value="hours">Toggle Hours/Dollars</button>  -->
    </td>

  </tr>

    @foreach($chart_hours as $var)
    <div class="sheepy">
      @isset($var)
      {!! $var->container() !!}
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
      {!! $var->script() !!}
      @endisset
    </div>


    <div lass="sheepy2">
      <table class="table table-striped">
        <thead>
          <tr>



            <td colspan="1">Total Spent:</td>
            <td colspan="1"><?php echo $var->options["CEGtimespenttodate"] . "-hr";  ?></td>

            <td colspan="1"><?php echo "$" . $var->options["total_project_dollars"];  ?></td>



            <td colspan="2">In House Budget:</td>
            <td colspan="3"><?php echo "$" . $var->options["dollarvalueinhouse"];  ?></td>

            <td colspan="4">




            </td>

            <td colspan="3">
              <div class="form-group col-md-4">
                <input type="text" value="1000000">
              </div>
            </td>



          </tr>
          <tr>






            <td colspan="1">Previous Month:</td>
            <td colspan="1"><?php echo $var->options["previous_month_project_hours"] . "-hr";  ?></td>

            <td colspan="1"><?php echo "$" . $var->options["previous_month_project_monies"];  ?></td>

            <td colspan="2">Energization Date:</td>
            <td colspan="3"><?php echo $var->options["dateenergization"];  ?></td>
            <td colspan="4">



            </td>

            <td colspan="1">CEG billed $XXXXXX on XXXX-XX-XX</td>


            <div class="form-group col-md-4">
            <label for="cegproposalauther">Bill This Amount</label>
          
            <a href="{{action('ProjectController@blah', $var->options['id'])}}" class="btn btn-success" method="POST">Edit</a>
            </div>



          </tr>
        </thead>
      </table>
    </div>

    <?php 
                      $x++;
                      ?>

    @endforeach






  @else
  <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="hours"><button class="btn btn-primary" name="button" type="submit" value="hours">ERROR</button>
  @endif
  @endisset
</form>



@endsection
