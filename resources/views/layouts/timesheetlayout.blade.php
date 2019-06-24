<!doctype html>
<style>
.table th {
    text-align: center;   
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
                        @for($row = 1; $row <= 10; $row++)
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="General and Admin" value="General and Admin" readonly>
                            </td>
                            @for($i = 1; $i <= 14; $i++)
                            <td>
                            <input type="number"  step="0.25" min="0"  class="form-control" id="generalandadminDay{{$i}}" name="generalandadminDay{{$i}}" value=""/>
                            </td>
                            @endfor
                            <td>
                                    <input type="text" class="form-control" name="General and Admin code" value="CEG" readonly>
                            </td>
                        </tr>   
                        @endfor 
                    </table>
        </div>
        <div class="row">
                <div class="form-group col-md-4">
                  <button id="add" class="btn btn-primary float-right">Add Row</button>
                </div>
              </div>





    <script type="text/javascript">

    $(document).ready(function() {
        $("#add").on('click', function() {
            addRow();
        }); 
    });

    function addRow(){
        var tr = '<tr>' +
                    '<td>' +
                     '<input type="text" class="form-control" name="added row" value="">' +
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
    </script>

  </body>
</html>