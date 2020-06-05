<!DOCTYPE html>
@extends('layouts.default')
<style>
  .mastersubmitbuttton {
  position: sticky;
  bottom: 0;
  }
    .switch {
              position: relative;
              display: inline-block;
              width: 30px;
              height: 17px;
            }
    .switch input { 
                  opacity: 0;
                  width: 0;
                  height: 0;
                }
    .slider {
                  position: absolute;
                  cursor: pointer;
                  top: 0;
                  left: 0;
                  right: 0;
                  bottom: 0;
                  background-color: #ccc;
                  -webkit-transition: .2s;
                  transition: .2;
                }

    .slider:before {
                  position: absolute;
                  content: "";
                  height: 13px;
                  width: 13px;
                  left: 2px;
                  bottom: 2px;
                  background-color: white;
                  -webkit-transition: .2s;
                  transition: .2s;
                }

                input:checked + .slider {
                  background-color: #2196F3;
                }

                input:focus + .slider {
                  box-shadow: 0 0 1px #2196F3;
                }

                input:checked + .slider:before {
                  -webkit-transform: translateX(13px);
                  -ms-transform: translateX(13px);
                  transform: translateX(13px);
                }

                /* Rounded sliders */
        .slider.round {
                  border-radius: 34px;
                }

        .slider.round:before {
                  border-radius: 50%;
                }
</style>

@section('page-title', 'Hours By Project Graph')
@section('content')
{{ old('project_id') }}
<?php $x=0; $id=0; ?>

@foreach($chart_variable as $var2)
  <?php $chart_variable[$x]=$var2; $x++; ?>
@endforeach
<br>
<form action="{{ route('pages.hoursgraph') }}" method="GET">
  @csrf
  <label class="switch">
    <input name="toggle_all" id="toggle_all" type="checkbox" value="all" @if($filter_all == true){{'checked'}}@endif>
    <span class="slider round"></span>
  </label><label>Toggle ALL Projects</label>
  <br>
  <label class="switch">
    <input name ="toggle_dollars" id="toggle_dollars" type="checkbox" value="dollars" @if($chart_units == 'dollars'){{'checked'}}@endif>
    <span class="slider round"></span>
  </label><label>Toggle Dollars</label>
  <br>
  <button class="btn btn-dark" id="switch_chart_button" name="switch_chart_button" type="submit">Toggle</button>
</form>
<form action="{{ route('pages.hoursgraph') }}" method="POST">
  @csrf
    @if ($chart_units == 'dollars') <!-- the page defaults to dollars, but then can be toggeled to hours with this button-->
       <!--<input class="btn btn-primary" name="switch_chart_button" type="hidden" value="dollars"><button class="btn btn-primary" name="button" type="submit" value="dollars">Toggle Hours/Dollars</button> -->
      @foreach($chart as $var) <!-- loops through graphs with hour data -->
      <?php
        if($filter_all == false){
        $fulltext = $var->options['title']['text'];
        $splittextfull = explode(", ", $fulltext);
        $split_pm = ltrim($splittextfull[2], 'PM is '); 
        if($split_pm != "" || $split_pm == null){
          if(auth()->user()->name != $split_pm) {
            continue;
          }
        }
      }?>
        <div id="chart" class="htmlgraphhours">
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
                <td colspan="1"><b>Total Spent (per billing code):                                                                      </b></td>
                <td colspan="1"><?php echo $var->options["CEGtimespenttodate"] . "-hr";  ?>                                                 </td>
                <td colspan="1"><?php echo "$" . $var->options["total_project_dollars"];  ?>                                                </td>
                <td colspan="2"><b>In House Budget:                                                                                      <b></td>
                <td colspan="2"><?php echo "$" . $var->options["dollarvalueinhouse"];  ?>                                                   </td>
                <td colspan="2"><b>Energization Date:                                                                                   </b></td>
                <td colspan="4">
                  <?php echo $var->options["dateenergization"];  ?> 
                </td>
              </tr>
              <tr>
                <td colspan="1"></td>
                <td colspan="1"><b>Billing History:</b></td>
                <td colspam="1"><?php echo date("F Y",strtotime("-3 month"))?></td>
              <td colspam="1"><?php if(isset($var->options["billing_data"])); //CODE FOR MONTHS HERE
              foreach($keys as $key){
                echo $key;
              }
              ?></td>
                <td colspan="1"><?php echo date("F Y",strtotime("-2 month"))?></td>
                <td colspam="1"></td>
                <td colspan="1"><?php echo date("F Y",strtotime("-1 month"))?></td>
                <td colspam="1"></td>
              </tr>
              <tr>
                <td colspan="1">                                                                                                            </td>
                <td colspan="1">Previous Month:                                                                                             </td>
                <td colspan="1"><?php echo $var->options["previous_month_project_hours"] . "-hr";  ?>                                       </td>
                <td colspan="1"><?php echo "$" . $var->options["previous_month_project_monies"];  ?>                                        </td>
                <td colspan="2">Bill this Amount:                                                                                          </td>
                <td colspan="4">          
                  <?php $id = $var->options['id']; ?> 
                  <input type="hidden" value="{{$var->options['id']}}" name="id_{{$x}}">
                  <input type="text" value="" name="text_{{$x}}"></td>
            
              <!--    <td colspan="2">Last Record Bill:                                                                                           </td>
                <td colspan="4">
                  <input type="text" value="<//?php echo "    $" . $var->options["last_bill_amount"] . " in " . substr($var->options["last_bill_month"], 0, 3);  ?>">
                </td> -->
              </tr>
          </table>
        </div>
        <?php $x++; ?>
      @endforeach

    @elseif ($chart_units == 'hours')

      @foreach($chart as $var) <!-- loops through graphs with dollar data --> 
      
      <?php
        if($filter_all == false){
        $fulltext = $var->options['title']['text'];
        $splittextfull = explode(", ", $fulltext);
        $split_pm = ltrim($splittextfull[2], 'PM is '); 
        if($split_pm != "" || $split_pm == null){
          if(auth()->user()->name != $split_pm) {
            continue;
          }
        }
      }?>
      <div id="dynamic_field">
        <div id="chart" class="htmlgraphdollars">
          @isset($var)
            {!! $var->container() !!}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
            {!! $var->script() !!}
          @endisset
        </div>
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
    <input class="btn btn-primary" name="mastersubmitbuttton" type="hidden" value="hours"><button href="{{action('ProjectController@blah', $var->options['id'])}}" class="btn btn-primary" method="POST" type="submit">Master Submit Buttton</button> 
  </div>
</form>
@endsection
