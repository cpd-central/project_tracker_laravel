<!doctype html>
<style>
.table th {
    text-align: center;
    font-size: 12px;   
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
        <form method="post">
          @csrf
        <div>
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
                          </tr>
                        </thead>
                        <?php $array = array('General and Admin', 'Staff Meetings and HR', 'Research and Training', 'Formal EDU', 'General Marketing') ?>
                        @for($row = 0; $row < count($array); $row++)
                      <tr id="row{{$row}}">
                            <td>
                                <input type="text" class="form-control" name="{{$array[$row]}}" value="{{$array[$row]}}" readonly>
                            </td>
                            @for($i = 1; $i <= 14; $i++)
                            <td>
                            <input type="number"  step="0.25" min="0"  class="form-control" id="row{{$row}}Day{{$i}}" name="row{{$row}}Day{{$i}}" value=""/>
                            </td>
                            @endfor
                            <td>
                                    <input type="text" class="form-control" name="{{$array[$row]}} code" value="code" readonly>
                            </td>
                        </tr>   
                        @endfor 
                    </table>
        </div>
        <div class="row">
                <div class="form-group col-md-4">
                  <button id="add" class="btn btn-primary float-right">Add Row</button>
                </div>
                <div class="form-group col-md-4">
                  <button id="remove" class="btn btn-danger float-right">Remove Row</button>
                </div>
                <div class="form-group col-md-4">
                  <button type="submit" id="submit" class="btn btn-success float-right">submit</button>
                </div>
              </div>
            </form>




    <script type="text/javascript">

    var r = 0;

    $(document).ready(function() {
      addTotal();
        $("#add").on('click', function() {
            r++;
            addRow();
        }); 

        $("#remove").on('click', function() {
            removeRow();  
        });

        $("#dynamic_field").on('change',function() {
            addTotal();
        });
    });

    function addRow(){
        var tr = '<tr id="dynamic_row'+r+'">' +
                    '<td>' +
                     '<input type="text" class="form-control" name="Product Description added row '+r+'" value="">' +
                     '</td>';
                     for(var i = 1; i <= 14; i++){
            var tr = tr + '<td>' +
                     '<input type="number"  step="0.25" min="0"  class="form-control" id="add'+i+'" name="add'+i+'" value=""/>' +
                            '</td>';
                    }
           var tr = tr + '<td>' +
                        '<input type="text" class="form-control" name="codeadd'+i+'" value="">' +
                     '</td>';
                     $('#dynamic_field').append(tr);
    }

    function removeRow(){
      if (r >= 1){
      $('#dynamic_row'+r+'').last().remove();  
      r--;
      }
    }

    function addTotal(){
      for(var w = 0; w < 5; w++){
        $('#total'+w+'').remove();
      }

      
      for(var w = 0; w < 5; w++){
        var total = 0;
        for(var n = 1; n <= 14; n++){
          var string = '#row'+w+'Day'+n;
          if(!isNaN(parseInt($(string).val()))) {
            total += parseInt($(string).val());
          }
        } 
        var td = '<td id="total'+w+'">' +
          '<input type="number"  step="0.25" min="0"  class="form-control" id="total'+w+'" name="total'+w+'" value="'+total+'" readonly />' +
          '</td>';
          $('#row'+w+'').append(td);
      }
    }     
    </script>

  </body>
</html>