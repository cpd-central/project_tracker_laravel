
//Sam's code for manage page. Console commands not working when script is outside of file. Moved to manage.blade.php

var clicks = 0;
var row = "<?php echo $c ?>" 
$(document).ready(function() {
    //addnewfield();

$("#addform").on('click', function() {
    addnewfield();    
}); 
}); 

    function addnewfield(){
        //$("#addform").on('click', function(){
            var name = window.prompt('Enter the name of the new Field: ');
            console.log(clicks);
            if (name != null && name != ""){
                clicks++;
                window.alert("name is :" + name);
                field = '<h5><b>' + name + '</b></h5>' + 
                    '<div class="row">' + 
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+ clicks+'person1">Engineer/Person 1</label>' + 
                            '<input type="text" class="form-control" id="row'+ clicks+'person1" name="row'+ clicks+'person1">' +
                        '</div>' +
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+ clicks+'person2">Drafter/Person 2</label>' + 
                            '<input type="text" class="form-control" id="row'+ clicks+'person2" name="row'+ clicks+'person2">' + 
                        '</div>' + 
                        '<div class="form-group col-md-4">' + 
                            '<label for="row'+ clicks+'due">Due Date</label>' + 
                            '<input type="date" class="form-control" id="row'+ clicks+'due" name="row'+ clicks+'due">' + 
                        '</div>' + 
                        '</div>' +
                        '<input type="hidden" id="row'+ clicks+'name" name="row'+ clicks+'name" value="'+ clicks+'" readonly />' +
                        '<input type="hidden" id="clicks" name="clicks" value="'+clicks+'" readonly />';
                $('#dynamic_field').append(field);
            }
        //});
    }
