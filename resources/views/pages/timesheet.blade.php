<?php 
if(isset($date)){
  $end = clone $date;
  $start = $date->sub(new DateInterval('P13D'));
  $start->setTime(0,0,0);
  $end->setTime(0,0,1);                       //By setting the day to an extra second, it includes the last day.
  $interval = \DateInterval::createFromDateString('1 day');
  $period = new DatePeriod($start, $interval, $end);
  $arr = array();
  foreach($period as $dt)
  {
    array_push($arr, $dt->format('j-M-y'));
  }
}

$reference_desc = array();
$reference_code = array();
foreach($reference_list[0]['codes'] as $key => $desc){
  array_push($reference_desc, $desc);
  array_push($reference_code, $key);
}
array_multisort($reference_desc, $reference_code);
?>

<!doctype html>
<style>

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button{
  -webkit-appearance: none;
  margin:0;
}




.table th {
    text-align: center;
    font-size: 12px;   
 }

.table td {
  padding: 0px;
  margin: 0px; 
  text-align: left;
}

.table input {
  font-size: 12px;
  width: 100%; 
  padding: 5px;
  margin: 1px;
}

 table.center {
    margin-left:auto; 
    margin-right:auto;
  }

 .fixed_header tbody{
  display:block;
  overflow:auto;
  height:200px;
  width:100%;
}

.fixed_header thead tr th{
  display:block;
  text-align: center;
}

.fixed_header thead{
  background: black;
  color:#fff;
}
 </style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<html>
  <title id="page-title">Timesheet</title>
  <head>
    @include('includes.navbar')
  </head> 
  <body>
        <div class="container">
        @if (isset($message) && $message == "Success! Timesheet was saved.")
          </br>
          <div class="alert alert-success">
            <p>{{$message}}</p>
          </div>
        @else
          </br>
        @endif
        <h2><b>Timesheet</b></h2>
        </div>
        <div>   
          <form method="POST">
              @csrf
                <table class="table table-sm overflow-auto" id="dynamic_field">
                        <thead>
                          <tr> 
                            <th>Product Description</th>
                            <?php for($i = 0; $i < 14; $i++){ ?>
                            <th><?= $arr[$i]?></th>
                            <?php } ?>
                            <th>Code</th>
                            <th style="width: 2.9%"></th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <?php $array = array('Holiday', 'PTO', 'General and Admin', 'Staff Meetings and HR', 'Research and Training', 'Formal EDU', 'General Marketing') ?>
                        <?php $code = array('CEG', 'CEG', 'CEG', 'CEG', 'CEGTRNG', 'CEGEDU', 'CEGMKTG') ?>
                        @for($row = 0; $row < count($array); $row++)  
                        @if(isset($timesheet))
                        <?php $codeOffset = $code[$row];         
                              $descOffset = $array[$row]; 
                              $dayarray = $timesheet['Codes'][$codeOffset][$descOffset]?>
                        @endif
                      <tr id="row{{$row}}">
                            <td style="width: 8%">
                                <input type="text" class="form-control" name="{{$array[$row]}}" value="{{$array[$row]}}" readonly>
                            </td>
                            @for($i = 1; $i <= 14; $i++)
                            <td style="width: 5%">
                            <input type="number" step="0.25" min="0" class="form-control" id="row{{$row}}Day{{$i}}" name="row{{$row}}[]" value="@if(isset($dayarray[$arr[$i-1]])){{$dayarray[$arr[$i-1]]}}@endif"/>
                            </td>
                            @endfor
                            <td style="width: 8%">
                            <input type="text" class="form-control" name="{{$array[$row]}} code" value="{{$code[$row]}}" readonly>
                            </td>
                            <td>
                            <button type="button" class="btn btn-warning text-warning">_</button>
                            </td>
                        </tr>   
                        @endfor 
                        @if(isset($timesheet['Codes']['Additional_Codes']))
                          @if(count($timesheet['Codes']['Additional_Codes']) > 0)
                          <!-- Number of default non-billables + 1 -->
                          <?php $row = 7 ?>
                            @for($i = 0; $i < count(array_keys($timesheet['Codes']['Additional_Codes'])); $i++)
                              <?php $codeKeyArray = array_keys($timesheet['Codes']['Additional_Codes']);
                              $code = $codeKeyArray[$i] ?>
                              @if(count($timesheet['Codes']['Additional_Codes']) > 0)
                                @for($index = 0; $index < count(array_keys($timesheet['Codes']['Additional_Codes'][$code])); $index++)
                                  <?php $desc = $timesheet['Codes']['Additional_Codes'][$code][$index]?> 
                                  <tr id="row{{$row}}">
                                      <td style="width: 8%">
                                          <input type="text" class="form-control" name="Product Description row {{$row}}" value="<?=$desc?>">
                                      </td>
                                      @for($day = 1; $day <= 14; $day++)
                                      <td style="width: 5%">
                                      <input type="number"  step="0.25" min="0"  class="form-control" id="row{{$row}}Day{{$day}}" name="row{{$row}}[]" value="@if(isset($timesheet['Codes'][$codeKeyArray[$i]][$desc][$arr[$day - 1]])){{$timesheet['Codes'][$codeKeyArray[$i]][$desc][$arr[$day - 1]]}}@endif"/>
                                      </td>   
                                      <?php $string = 'Codes' ?>  
                                      @endfor
                                      <td style="width: 8%">
                                        <input type="text" class="form-control" name="codeadd{{$row}}" value="<?=$codeKeyArray[$i]?>">
                                      </td>
                                      <td> 
                                          <button type="button" id="row{{$row}}" class="btn btn-danger btn_remove">-</button>
                                      </td>
                                  </tr>   
                                  <?php $row++; ?>
                                @endfor
                              @endif
                            @endfor
                          @endif
                        @endif
                        <?php
                        foreach($arr as $date) { ?>
                          <input type="hidden" name="daterange[]" value="<?=$date?>"/>
                        <?php }?>
                    </table>
        </div>
        <div class="row">
                <div class="form-group col-md-4">
                  <button type="button" id="add" class="btn btn-primary float-right">Add Row</button>
                </div>
                <div class="form-group col-md-4">
                    <button type="submit" id="remove" class="btn btn-success float-right" onclick="return confirm('Make sure your descriptions and code is correct. Are you sure you want to submit?')">Submit</button>
                  </div>
              </div>
            </form>
              <table class="fixed_header center" id="reference_field">
              </table>
    <script type="text/javascript">
    var row = "<?php echo $row ?>" -1;
    var reference_desc = <?php echo json_encode($reference_desc); ?>;
    var reference_code = <?php echo json_encode($reference_code); ?>;
    var array = [];
    make_array();
    $(document).ready(function() {
      columnTotal();
      addRowTotal();
      hiddenField();
      referenceTable();
        $("#add").on('click', function() {
            row++;
            addRow();
            columnTotal();
            addRowTotal();
            hiddenField();       
        }); 

        $("#dynamic_field").on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#'+button_id+'').remove();
            hiddenField();
            columnTotal();
        });

        $("#dynamic_field").on('change',function() {
          columnTotal();
          addRowTotal();
        });

        $(window).keydown(function(event){
          if(event.keyCode == 13) {
            event.preventDefault();
            return false;
          }
        });

    });

    function make_array(){  
      for(var y = 0; y < reference_desc.length; y++){
        array[reference_desc[y]] = reference_code[y];
      }
    }

    function referenceTable(){
        var tableRef = '<thead>' +
                  '<tr>' +
                    '<th>' +
                      'Reference List' +
                    '</th>' +
                  '</tr>' +
                '</thead>';
        for(var z = 0; z < reference_desc.length; z++){
          var tableRef = tableRef + '<tr>' +
                  '<td>' +
                      array[reference_desc[z]] + 
                  '</td>' +
                  '<td>' +
                      reference_desc[z] +
                  '</td>' +
                '</tr>';
        }
      $('#reference_field').append(tableRef);
    }

    function addRow(){
        var tr = '<tr id="row'+row+'">' +
                    '<td>' +
                     '<input type="text" class="form-control" name="Product Description row '+row+'" value="" required>' +
                     '</td>';
                     for(var i = 1; i <= 14; i++){
            var tr = tr + '<td>' +
                     '<input type="number"  step="0.25" min="0"  class="form-control" id="row'+row+'Day'+i+'" name="row'+row+'[]" value=""/>' +
                            '</td>';
                    }
           var tr = tr + '<td>' +
                        '<input type="text" class="form-control" name="codeadd'+row+'" value="" required>' +
                     '</td>' +
                     '<td>' +
                        '<button type="button" id="row'+row+'" class="btn btn-danger btn_remove">-</button>' +
                     '</td>';
                     $('#dynamic_field').append(tr);
    }

    function addRowTotal(){
      for(var w = 0; w <= row; w++){
        $('#total'+w+'').remove();
      }

      
      for(var w = 0; w <= row; w++){
        var total = 0;
        for(var n = 1; n <= 14; n++){
          var string = '#row'+w+'Day'+n;
          if(!isNaN(parseFloat($(string).val()))) {
            total += parseFloat($(string).val());
          }
        } 
        var td = '<td id="total'+w+'" style="width: 5%">' +
          '<input type="number"  step="0.25" min="0"  class="form-control" id="total'+w+'" name="total'+w+'" value="'+total+'" readonly />' +
          '</td>';
          $('#row'+w+'').append(td);
      }
    }     

    function columnTotal(){  
      var grand_total = 0;
      for(var x = 0; x <= 17; x++){
        $('#coltotal'+x+'').remove();
      }

      var tr = '<td id="coltotal0"></td>'
      for(var n = 1; n <= 14; n++){
        var total = 0;
        for(var w = 0; w <= row; w++){
          var string = '#row'+w+'Day'+n+'';
          if(!isNaN(parseFloat($(string).val()))) {
            total += parseFloat($(string).val());
          }
        }
        var td = '<td id="coltotal'+n+'">' +
          '<input type="number"  step="0.25" min="0"  class="form-control" id="coltotal'+n+'" name="coltotal'+n+'" value="'+total+'" readonly />' +
          '</td>';
          tr = tr + td;
          grand_total += total;
      }
      //Grand Total of all hours field, bottom right of table.
      tr = tr + '<td id="coltotal15"></td><td id="coltotal16"></td><td id="coltotal17">' +
            '<input type="number"  step="0.25" min="0"  class="form-control" id="coltotal15" name="coltotal15" value="'+grand_total+'" readonly />' +
          '</td>';
      $('#dynamic_field').append(tr);
    }

    //Need this row total so laravel knows how many rows to look for data when it submits.
    function hiddenField(){
      $('#total_rows').remove();
      var input = '<tr id="total_rows">' +
                    '<td>' +
                    '<input type="hidden" id="row_total" name="row_total" value="'+row+'" readonly />' +
                    '</td>' +
                    '</tr>';
      $('#dynamic_field').append(input);
    }
    </script>
  </body>
</html>


