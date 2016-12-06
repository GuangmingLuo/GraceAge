/*
 * Automatically sorts on name
 * Default shows first 10 entries
 * Allows to search on name.
 */
$(document).ready(function(){
    //Makes the datatable
    var table = $('#personal-datatable').DataTable();
    
    //Creates the dialog pop-up.
    $("#display-scores").dialog({
            autoOpen: false,
            buttons: {
                OK: function() {$(this).dialog("close");}
            }
    });
    
    //When clicked on a table row, this function is called, which opens the dialog screen.
    $('#personal-datatable tbody').on('click', 'tr', function(){
        var data = table.row(this).data();
        window.location.href = "personal?username="+data[0];  //This displays the scores in Christophe's table.
        //$('#display-scores').dialog("open");    //Opens a dialog screen. This should contain the data.
    });
});