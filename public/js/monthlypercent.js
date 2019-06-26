var datentp;
var dateenergization;
var numFields;
var start_month;
var start_year;

/**
 * Sets up the document to be ready as soon as the page loads up.
 */
$(document).ready(function() {
    //if datentp field is focused on then unfocused, formats the date then checks if both dates inputted. 
    $("#datentp").on('blur', function() {
      removeFields();
      datentp = format('#datentp');
      dateenergization = format('#dateenergization');
      check();
    });

    //if dateenergization field is focused on then unfocused, formats the date then checks if both dates inputted. 
    $("#dateenergization").on('blur', function() {
      removeFields();
      datentp = format('#datentp');
      dateenergization = format('#dateenergization');
      check();
    });

    //if fields are changed, add the total % box
    $("#dynamic_field").on('change', function() {
      if(datentp != 0 && dateenergization != 0) {
        addTotalBox();
      }
    });
  });

  /**
   * If both dates are inputted, converts them to Date variables and calls monthDiff
   * and calculateFields on them.
   */
  function check() {
    if(datentp != 0 && dateenergization != 0) {
      datentp = new Date(datentp);                   
      dateenergization = new Date(dateenergization); 
      calculateFields(monthDiff(datentp, dateenergization));
    }
  }

  /**
   * Reformats the date received in order to be converted to Date variable.
   * If it's null, returns 0.
   * @param {*} id 
   */
  function format(id) {
    var values = $(id).val();
    var dateArr = values.split('-');
    if (dateArr.length > 1) {
    return (dateArr[1] + '/' + dateArr[2] + '/' + dateArr[0]);
    }
    return 0;
  }

  /**
   * removes all rows in the dynamic_field.
   */
  function removeFields() {
    $("#dynamic_field tr").remove(); 
  }

  /**
   * Gets the month difference between two dates.
   * @param {*} dateFrom 
   * @param {*} dateTo 
   */
  function monthDiff(dateFrom, dateTo) {
    return dateTo.getMonth() - dateFrom.getMonth() + 
    (12 * (dateTo.getFullYear() - dateFrom.getFullYear())) + 1;
  }

  /**
   * Calculates the number of input fields needed using the $var variable which represents the number of
   * months. Assigns 4 input fields to a row.
   * @param {*} $var 
   */
  function calculateFields($var){
    numFields = $var;
    var row = $var / 4;             //4 text boxes per row
    var mod = $var % 4;

    start_month = datentp.getMonth();
    start_year = datentp.getFullYear();

    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var i = start_month;
    var year = start_year;

    //Caption
      var tr =  '<tr>' +
                    '<td colspan="100%">' +
                      '<h5 class="text-center">'+"Input percents as decimals below. For reference: 0.05 = 5%, 0.7 = 70%, 1 = 100%"+'</h5>'
                    '</td>' +
              '</tr>';
              $('#dynamic_field').append(tr);
    for(var k = 1; k <= row; k++) {
      var tr = '<tr>';
      for (var j = 0; j < 4; j++) {
        if(i > 11){
          i = 0;
          year = year + 1;
        }
          var tr = tr + '<td>' + months[i] + ' ' + year + '</td>' +
                    '<td>'+
                        '<input type="number" step="0.01" min="0.00" max="1.00" class="form-control" id="month'+i+'year'+year+'" name="monthly_percent[]" value="" />' +
                    '</td>';
                    i++;
      }
          var tr = tr + '</tr>';          
      $('#dynamic_field').append(tr);
    }
    if(mod > 0) {
      var tr = '<tr>';
      for (var w = 0; w < mod; w ++) {
        if(i > 11){
          i = 0;
          year = year + 1;
        }
        var tr = tr + '<td>' + months[i] + ' ' + year + '</td>' +
                    '<td>'+
                        '<input type="number" step="0.01" min="0.00" max="1.00" class="form-control" id="month'+i+'year'+year+'" name="monthly_percent[]" value="" />' +
                    '</td>';
                    i++;
      }
      var tr = tr + '</tr>';          
      $('#dynamic_field').append(tr);
    }
    addTotalBox();
  }
  
/**
 * Adds all the inputted fields together and displays that total in a box which is colored red if its not equal to
 * 100%, and colored green if it is.
 */
  function addTotalBox() {
    $("#total_box").remove();
    if($('#datentp').val() && $('#dateenergization').val()){
      datentp = new Date($('#datentp').val());
      dateenergization = new Date($('#dateenergization').val());
      numFields = monthDiff(datentp, dateenergization);
    }
    var i =  parseInt(datentp.getMonth());
    var year = parseInt(datentp.getFullYear());
    var total = 0;
    for(j=0; j < numFields; j++){
      if(i > 11){
          i = 0;
          year = year + 1;
        }
      var string = '#month'+i+'year'+year;
      if(!isNaN(parseFloat($(string).val()))) {
        total += parseFloat($(string).val());
        i++;
      }
      else{
        i++;
      }
    }
    var totalPercentDisplay = Math.round(total*100);
    if(total < 1.01 && total > 0.999999){
      var tr = '<tr id="total_box">' +
                    '<td>' +
                      '<div class="p-3 mb-2 bg-success text-white">'+totalPercentDisplay+"%"+'</div>'
                    '</td>' +
              '</tr>';
        $('#dynamic_field').append(tr);   
    }
    else{
      var tr = '<tr id="total_box">' +
                    '<td>' +
                      '<div class="p-3 mb-2 bg-danger text-white">'+totalPercentDisplay+"%"+'</div>'
                    '</td>' +
              '</tr>';
        $('#dynamic_field').append(tr);       
    }
  }