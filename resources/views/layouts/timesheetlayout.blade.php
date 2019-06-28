<!doctype html>
<style>
.table th {
    text-align: center;
    font-size: 12px;   
 }

 .table td {
  padding: 0px;
  text-align: left;
}

 .table input {
   font-size: 12px;
 }

 </style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<html>
  <title id="page-title">@yield('page-title')</title>
  <head>
    @include('includes.navbar')
  </head>
  <body>
        <div class="container">
        </br>
        </br>
        <h2><b>@yield('title')</b></h2>
        </div>
        <div>
          <form method="POST">
            @csrf
                <table class="table table-sm overflow-auto" id="dynamic_field">
                        <thead>
                          <tr> 
                            <th>Product Description</th>
                            <th>Day 1</th>
                            <th>Day 2</th>
                            <th>Day 3</th>
                            <th>Day 4</th>
                            <th>Day 5</th>
                            <th>Day 6</th>
                            <th>Day 7</th>
                            <th>Day 8</th>
                            <th>Day 9</th>
                            <th>Day 10</th>
                            <th>Day 11</th>
                            <th>Day 12</th>
                            <th>Day 13</th>
                            <th>Day 14</th>
                            <th>Code</th>
                            <th style="width: 2.9%"></th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <?php $array = array('General and Admin', 'Staff Meetings and HR', 'Research and Training', 'Formal EDU', 'General Marketing') ?>
                        <?php $code = array('CEG', 'CEG', 'CEGTRNG', 'CEGEDU', 'CEGMKTG') ?>
                        @for($row = 0; $row < count($array); $row++)  
                        @if(isset($timesheet))
                        <?php $codeOffset = $code[$row];         
                              $descOffset = $array[$row];
                              $dayarray = $timesheet[$codeOffset][$descOffset] ?>
                        @endif
                      <tr id="row{{$row}}">
                            <td style="width: 12%">
                                <input type="text" class="form-control" name="{{$array[$row]}}" value="{{$array[$row]}}" readonly>
                            </td>
                            @for($i = 1; $i <= 14; $i++)
                            <td style="width: 5%">
                            <input type="number"  step="0.25" min="0"  class="form-control" id="row{{$row}}Day{{$i}}" name="row{{$row}}[]" value="@if(isset($dayarray[$i - 1])){{$dayarray[$i - 1]}}@endif"/>
                            </td>
                            @endfor
                            <td style="width: 7%">
                            <input type="text" class="form-control" name="{{$array[$row]}} code" value="{{$code[$row]}}" readonly>
                            </td>
                            <td>
                            <button type="button" class="btn btn-warning text-warning">_</button>
                            </td>
                        </tr>   
                        @endfor 
                        @if(isset($timesheet['Additional_Codes']))
                          @if(count($timesheet['Additional_Codes']) > 0)
                          <?php $row = 5 ?>
                            @for($i = 0; $i < count(array_keys($timesheet['Additional_Codes'])); $i++)
                              <?php $codeKeyArray = array_keys($timesheet['Additional_Codes']);
                              $code = $codeKeyArray[$i] ?>
                              @if(count($timesheet[$code]) > 0)
                                @for($index = 0; $index < count(array_keys($timesheet[$code])); $index++)
                                  <?php $productDesc = array_keys($timesheet[$code])?> 
                                  <tr id="row{{$row}}">
                                      <td style="width: 12%">
                                          <input type="text" class="form-control" name="Product Description row {{$row}}" value="<?=$productDesc[$index]?>">
                                      </td>
                                      @for($day = 1; $day <= 14; $day++)
                                      <td style="width: 5%">
                                        <input type="number"  step="0.25" min="0"  class="form-control" id="row{{$row}}Day{{$day}}" name="row{{$row}}[]" value="<?=$timesheet[$code][$productDesc[$index]][$day - 1]?>"/>
                                      </td>
                                      @endfor
                                      <td style="width: 7%">
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
                    </table>
        </div>
        <div class="row">
                <div class="form-group col-md-4">
                  <button type="button" id="add" class="btn btn-primary float-right">Add Row</button>
                </div>
                <div class="form-group col-md-4">
                    <button type="submit" id="remove" class="btn btn-success float-right">Submit</button>
                  </div>
              </div>
            </form>




    <script type="text/javascript">
    var row = "<?php echo $row ?>" -1;
    total_button_presses = 0;
    $(document).ready(function() {
      columnTotal();
      addRowTotal();
      hiddenField();
        $("#add").on('click', function() {
            row++;
            addRow();
            columnTotal();
            addRowTotal();
            hiddenField();       
            total_button_presses++;
        }); 

        $("#dynamic_field").on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#'+button_id+'').remove();
            columnTotal();
        });

        $("#dynamic_field").on('change',function() {
          columnTotal();
          addRowTotal();
        });
    });

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

    function hiddenField(){
      $('#total_button_presses_row').remove();
      var input = '<tr id="total_button_presses_row">' +
                    '<td>' +
                    '<input type="hidden" id="total_button_presses" name="total_button_presses" value="'+total_button_presses+'" readonly />' +
                    '</td>' +
                    '<td>' +
                    '<input type="hidden" id="row_total" name="row_total" value="'+row+'" readonly />' +
                    '</td>' +
                    '</tr>';
      $('#dynamic_field').append(input);
    }
    </script>
  </body>
</html>