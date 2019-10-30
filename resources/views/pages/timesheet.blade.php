<?php 
if(isset($start_end_dates)) {
  $start = $start_end_dates['start_date'];
  $end = $start_end_dates['end_date'];
  $start->setTime(0,0,0);
  $end->setTime(0,0,1); 
  $interval = \DateInterval::createFromDateString('1 day');
  $period = new DatePeriod($start, $interval, $end);
  $arr = array();
  $header_arr = array();
  foreach($period as $dt)
  {
    array_push($arr, $dt->format('j-M-y'));
    array_push($header_arr, $dt->format('D j-M-y'));
  }
}
else {
    if (isset($date)){
    $end = clone $date;
    //subtract 13 days from today ($date gets passed in from controller)
    $start = $date->sub(new DateInterval('P13D'));
    $start->setTime(0,0,0);
    $end->setTime(0,0,1);                       //By setting the day to an extra second, it includes the last day.
    $interval = \DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start, $interval, $end);
    $arr = array();
    $header_arr = array(); 
    foreach($period as $dt)
    {
      array_push($arr, $dt->format('j-M-y'));
      array_push($header_arr, $dt->format('D j-M-y'));
    }
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
  text-align: left;
}

.table input {
  font-size: 12px;
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
  <body onclick="findStartField()">
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
        </br> 
        <div class="container"> 
        <form method="POST">
          @csrf
          <div class="row">
            <div class="form-group col-md-3"> 
              <label for="startdate">Start Date:</label> 
              <input type="date" class="form-control" id="startdate" name="startdate" value="{{ $start->format('Y-m-d') }}"> 
            </div>
            <div class="form-group col-md-3">
              <label for="enddate">End Date:</label>
              <input type="date" class="form-control" id="enddate" name="enddate" value="{{ $end->format('Y-m-d') }}">
            </div> 
            <div class="form-group col-md-2">
              <button type="submit" name="action" class="btn btn-primary float-right" value="date_range">Update Date Range</button> 
            </div> 
            <div class="form-group col-md-2">
              <button type="submit" name="action" class="btn btn-primary float-right" value="date_reset">Reset Date Range</button> 
            </div> 
            </div>
          </div> 
        </div> 
        <div>   
                <table class="table table-sm overflow-auto" id="dynamic_field">
                        <thead>
                          <tr> 
                            <th>Product Description</th>
                            <?php 
                            $i = 0; 
                            foreach($header_arr as $head_part) {?>
                            <th><?= $header_arr[$i]?></th>
                            <?php 
                            $i++;   
                            } 
                            ?>
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
                            <td style="width: 8%; min-width: 125px;">
                                <input type="text" class="form-control" name="{{$array[$row]}}" value="{{$array[$row]}}" readonly>
                            </td>
                            @for($i = 1; $i <= count($header_arr); $i++)
                            <td style="width: 3%; min-width: 60px;">
                            <input type="number" step="0.25" min="0" class="form-control" id="row{{$row}}Day{{$i}}" name="row{{$row}}[]" value="@if(isset($dayarray[$arr[$i-1]])){{$dayarray[$arr[$i-1]]}}@endif"/>
                            </td>
                            @endfor
                            <td style="width: 8%; min-width: 125px;">
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
                                          <input type="text" class="form-control" id="row{{$row}}Day0" name="Product Description row {{$row}}" value="<?=$desc?>">
                                      </td>
                                      @for($day = 1; $day <= 14; $day++)
                                      <td style="width: 3%">
                                      <input type="number"  step="0.25" min="0"  class="form-control" id="row{{$row}}Day{{$day}}" name="row{{$row}}[]" value="@if(isset($timesheet['Codes'][$codeKeyArray[$i]][$desc][$arr[$day - 1]])){{$timesheet['Codes'][$codeKeyArray[$i]][$desc][$arr[$day - 1]]}}@endif"/>
                                      </td>   
                                      <?php $string = 'Codes' ?>  
                                      @endfor
                                      <td style="width: 8%">
                                        <input type="text" class="form-control" id="row{{$row}}Day15" name="codeadd{{$row}}" value="<?=$codeKeyArray[$i]?>">
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
                    <button type="submit" name="action" class="btn btn-success float-right" onclick="return confirm('Make sure your descriptions and code is correct. Are you sure you want to submit?')" value="submit">Submit</button>
                  </div>
              </div>
            </form>
            <div class="container" id="reference_field">
            
            </div>
            <div class="container" id="sort_button">
              
            </div>
    <script type="text/javascript">
    var row = "<?php echo $row ?>" -1;
    var reference_desc = <?php echo json_encode($reference_desc); ?>;
    var reference_code = <?php echo json_encode($reference_code); ?>;
    var array = [];
    var description_code_sort = 'code';
    make_array(description_code_sort);
    $(document).ready(function() {
      columnTotal();
      addRowTotal();
      hiddenField();
      referenceTable();
        $('#sort_button').on('click', '#sort', function() { 
          var sort_term = document.getElementById("sort").value; 
          console.log(sort_term); 
          make_array(sort_term);
          referenceTable(sort_term);
        })
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


    function make_array(description_code_sort){  
      for(var y = 0; y < reference_desc.length; y++){
        array[reference_desc[y]] = reference_code[y];
      }
      sorted_codes = Object.values(array).sort(); 
      sorted_descriptions = Object.keys(array).sort();

      if (description_code_sort === 'description') {
        sorted_array = {};
        for (var i = 0; i < Object.keys(array).length; i++) {
          sorted_array[sorted_descriptions[i]] = array[sorted_descriptions[i]]
        }
      }
      else if (description_code_sort === 'code') { 
        sorted_array = {} 
        for (var j = 0; j < Object.keys(array).length; j++) {
          sorted_array[Object.keys(array).find(key => array[key] === sorted_codes[j])] = sorted_codes[j];
        }
      }

    }

    function referenceTable(sort_term){
        var table = document.getElementById('reference_list');
        var button = document.getElementById('button_div'); 

        if (button != null) {
          var child = button.lastElementChild;

          while (child) {
            child = child.lastElementChild;
          }

          button.remove();
        }

        if (table != null) { 
          var child = table.lastElementChild;
           
          while (child) {
            child = child.lastElementChild;
          } 
           
          table.remove();
        } 
        var tableRef = '<table class="fixed_header center" id="reference_list">' + 
                  '<thead>' +
                  '<tr>' +
                    '<th>' +
                      'Reference List' +
                    '</th>' +
                  '</tr>' +
                '</thead>';
        
        for(var z = 0; z < reference_desc.length; z++){
          var tableRef = tableRef + '<tr>' +
                  '<td>' +
                      sorted_array[Object.keys(sorted_array)[z]] + 
                  '</td>' +
                  '<td>' +
                      Object.keys(sorted_array)[z] +
                  '</td>' +
                '</tr>';
        }
        var tableBreak = '</table>'; 
        tableRef = tableRef + tableBreak;

        if (sort_term === 'description') {
          var sortButton = '<div class="row" id="button_div"><div class="col text-center"><button type="button" id="sort" class="btn btn-primary" value="code">Sort By Code</button></div></div>';         
        } 
        else if (sort_term === 'code') {
          var sortButton = '<div class="row" id="button_div"><div class="col text-center"><button type="button" id="sort" class="btn btn-primary" value="description">Sort By Description</button></div></div>';
        }
        else {
          var sortButton = '<div class="row" id="button_div"><div class="col text-center"><button type="button" id="sort" class="btn btn-primary" value="description">Sort By Description</button></div></div>';
        }
      
      $('#reference_field').append(tableRef);
      $('#sort_button').append(sortButton);        
    }

    function addRow(){
        var tr = '<tr id="row'+row+'">' +
                    '<td>' +
                     '<input type="text" class="form-control" name="Product Description row '+row+'" id="row' + row + 'Day0" value="" required>' +
                     '</td>';
                     for(var i = 1; i <= 14; i++){
            var tr = tr + '<td>' +
                     '<input type="number"  step="0.25" min="0"  class="form-control" id="row'+row+'Day'+i+'" name="row'+row+'[]" value=""/>' +
                            '</td>';
                    }
           var tr = tr + '<td>' +
                        '<input type="text" class="form-control" name="codeadd'+row+'" id="row' + row + 'Day15" value="" required>' +
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
        var td = '<td id="total'+w+'" style="width: 5%; min-width: 60px;">' +
          '<input type="number"  step="0.25" min="0"  class="form-control" id="total'+w+'" name="total'+w+'" value="'+total+'" readonly />' +
          '</td>';
          $('#row'+w+'').append(td);
      }
    }     

    function columnTotal(){  
      var grand_total = 0;
      var num_columns = "<?php echo count($arr) ?>";
      for(var x = 0; x <= num_columns + 3; x++){
        $('#coltotal'+x+'').remove();
      }

      var tr = '<td id="coltotal0"></td>'
      for(var n = 1; n <= num_columns; n++){
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

    //disable arrow key increment
    document.getElementById('dynamic_field').addEventListener('keydown', function(e) {
      if (e.which === 38 || e.which === 40) {
        e.preventDefault();
      }
    })

    //navigate with arrow keys
    function findStartField(is_tab=null) {
      var start = document.activeElement.id;
      x = Number(start.substring(start.lastIndexOf("w") + 1, start.lastIndexOf("D")));
      y = Number(start.substr(start.indexOf("y") + 1));

      if (is_tab != null) {
        y = y + 1;
      } 
    } 

    document.getElementById("dynamic_field").addEventListener("click", findStartField);
  
    function dotheneedful(new_x, new_y) {
        var new_id = String("row" + new_x + "Day" + new_y);
        sibling = document.getElementById(new_id);
        if (sibling != null) {
          sibling.focus();
          findStartField(null); 
        }
    } 

    document.onkeydown = checkKey;

    function checkKey(e) {
      e = e || window.event;
      if (e.keyCode == '38') {
        //up arrow
        var new_x = x - 1;
        var new_y = y;
        dotheneedful(new_x, new_y);
      
      } else if (e.keyCode == '40' || e.keyCode == '13') {
        //down arrow or enter 
        var new_x = x + 1;
        var new_y = y;
        dotheneedful(new_x, new_y);
      
      } else if (e.keyCode == '37') {
        // left arrow 
        var new_x = x;
        var new_y = y - 1;
        dotheneedful(new_x, new_y);

      } else if (e.keyCode == '39') {
        // right arrow
        var new_x = x;
        var new_y = y + 1;
        dotheneedful(new_x, new_y);
      
      } 
      else if (e.keyCode == '9') {
        // when tab gets pressed, the active element will shift, so we don't need to do that.  we just need to get the new id.
        findStartField(true);
      }
    }
    </script>
  </body>
</html>


