
var clicks = 0;
$(document).ready(function() {
    addnewfield();
});

function addnewfield(){
    $("#addform").on('click', function(){
        clicks++;
        var name = window.prompt('Enter the name of the new Field: ');
        field = '<h5><b>' + name + '</b></h5>' + 
                '<div class="row">' + 
                    '<div class="form-group col-md-4">' + 
                        '<label for="person1field' + clicks + '">Engineer/Person 1</label>' + 
                        '<input type="text" class="form-control" name="person1field' + clicks + '">' + 
                    '</div>' +
                    '<div class="form-group col-md-4">' + 
                        '<label for="person2field' + clicks +'">Drafter/Person 2</label>' + 
                        '<input type="text" class="form-control" id="person2field' + clicks +'" name="person2field' + clicks + '">' + 
                    '</div>' + 
                    '<div class="form-group col-md-4">' + 
                        '<label for="duefield' + clicks +'">Due Date</label>' + 
                        '<input type="date" class="form-control" id="duefield' + clicks +'" name="duefield' + clicks + '">' + 
                    '</div>' + 
                    '</div>' +
                    '<input type="hidden" id="namefield' + clicks + '" name="namefield'+ clicks + '" value="'+name+'" readonly />' +
                    '<input type="hidden" id="clicks" name="clicks" value="'+clicks+'" readonly />';
        $('#dynamic_field').append(field);
    });
}