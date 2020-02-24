<!DOCTYPE html>
@extends('layouts.default')

@section('page-title', 'Hours By Project Graph')
@section('content')
{{ old('project_id') }}
<?php $x=0; $id=0; ?>

@foreach($chart_variable as $var2)
  <?php $chart_variable[$x]=$var2; $x++; ?>
@endforeach

<form action="{{ route('pages.hoursgraph') }}" method="POST">
  @csrf
    @if ($chart_units == 'dollars') <!-- the page defaults to dollars, but then can be toggeled to hours with this button-->
      <!-- <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="dollars"><button class="btn btn-primary" name="button" type="submit" value="dollars">Toggle Hours/Dollars</button> -->

      @foreach($chart_hours as $var) <!-- loops through graphs with hour data -->
        <div class="htmlgraphhours">
          @isset($var)
            {!! $var->container() !!}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
            {!! $var->script() !!}
          @endisset
        </div>
        <div class="summaryofinformationforhours"> <!-- data below graphs -->
          <table class="table table-striped">
            <thead>
            </thead>
              <tr>
                <td colspan="1"><a href="{{action('ProjectController@edit_project', $var->options['id'])}}" class="btn btn-warning">Edit</a></td>
                <td colspan="1">Total Spent (per billing code):                                                                             </td>
                <td colspan="1"><?php echo $var->options["CEGtimespenttodate"] . "-hr";  ?>                                                 </td>
                <td colspan="1"><?php echo "$" . $var->options["total_project_dollars"];  ?>                                                </td>
                <td colspan="2">In House Budget:                                                                                            </td>
                <td colspan="3"><?php echo "$" . $var->options["dollarvalueinhouse"];  ?>                                                   </td>
                <td colspan="2">Bill This Amount:                                                                                           </td>
                <td colspan="4">
                  <?php $id = $var->options['id']; ?> 
                  <input type="hidden" value="{{$var->options['id']}}" name="id_{{$x}}">
                  <input type="text" value="" name="text_{{$x}}">
                </td>
              </tr>
              <tr>
                <td colspan="1">                                                                                                            </td>
                <td colspan="1">Previous Month:                                                                                             </td>
                <td colspan="1"><?php echo $var->options["previous_month_project_hours"] . "-hr";  ?>                                       </td>
                <td colspan="1"><?php echo "$" . $var->options["previous_month_project_monies"];  ?>                                        </td>
                <td colspan="2">Energization Date:                                                                                          </td>
                <td colspan="3"><?php echo $var->options["dateenergization"];  ?>                                                           </td>
                <td colspan="2">Last Record Bill:                                                                                           </td>
                <td colspan="4">
                  <input type="text" value="<?php echo "    $" . $var->options["last_bill_amount"] . " in " . substr($var->options["last_bill_month"], 0, 3);  ?>">
                </td>
              </tr>
          </table>
        </div>
        <?php $x++; ?>
      @endforeach

    @elseif ($chart_units == 'hours')
      <tr>
        <td>
          <!-- <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="hours"><button class="btn btn-primary" name="button" type="submit" value="hours">Toggle Hours/Dollars</button>  -->
        </td>
      </tr>

      @foreach($chart_dollars as $var) <!-- loops through graphs with dollar data --> 
        <div class="htmlgraphdollars">
          @isset($var)
            {!! $var->container() !!}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
            {!! $var->script() !!}
          @endisset
        </div>

        <div class="summaryofinformationfordollars"> <!-- data below graphs -->
          <table class="table table-striped">
            <thead>
              <tr>
                <td colspan="1"><a href="{{action('ProjectController@edit_project', $var->options['id'])}}" class="btn btn-warning">Edit</a></td>
                <td colspan="1">Total Spent (per billing code):                                                                             </td>
                <td colspan="1"><?php echo $var->options["CEGtimespenttodate"] . "-hr";  ?>                                                 </td>
                <td colspan="1"><?php echo "$" . $var->options["total_project_dollars"];  ?>                                                </td>
                <td colspan="2">In House Budget:                                                                                            </td>
                <td colspan="3"><?php echo "$" . $var->options["dollarvalueinhouse"];  ?>                                                   </td>
                <td colspan="2">Bill This Amount:                                                                                           </td>
                <td colspan="4">
                  <?php $id = $var->options['id']; ?> 
                  <input type="hidden" value="{{$var->options['id']}}" name="id_{{$x}}">
                  <input type="text" value="" name="text_{{$x}}">
                </td>
               </tr>
              <tr>
                <td colspan="1">                                                                                                            </td>
                <td colspan="1">Previous Month:                                                                                             </td>
                <td colspan="1"><?php echo $var->options["previous_month_project_hours"] . "-hr";  ?>                                       </td>
                <td colspan="1"><?php echo "$" . $var->options["previous_month_project_monies"];  ?>                                        </td>
                <td colspan="2">Energization Date:                                                                                          </td>
                <td colspan="3"><?php echo $var->options["dateenergization"];  ?>                                                           </td>
                <td colspan="2">Last Record Bill:                                                                                           </td>
                <td colspan="4">
                  <input type="text" value="<?php echo "    $" . $var->options["last_bill_amount"] . " in " . substr($var->options["last_bill_month"], 0, 3);  ?>">
                </td>
              </tr>
            </thead>
          </table>
        </div>
        <?php $x++; ?>
      @endforeach
    @endif

  <input type="hidden" value="{{$x}}" name="graph_count"> <!-- master button for submitting billing number to database -->
  <div class="mastersubmitbuttton">
    <input class="btn btn-primary" name="switch_chart_button" type="hidden" value="hours"><button href="{{action('ProjectController@blah', $var->options['id'])}}" class="btn btn-primary" method="POST" type="submit">Master Submit Buttton</button> 
  </div>
</form>
@endsection
