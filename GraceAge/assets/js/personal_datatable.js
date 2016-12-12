/*
 * Automatically sorts on name
 * Default shows first 10 entries
 * Allows to search on name.
 */
$(document).ready(function(){
    //Makes the datatable
    var table = $('#personal-datatable').DataTable();
       
    //$("td[colspan=4]").find("p").hide();
    
    //When clicked on a table row, this function is called, which opens the dialog screen.
    $('#personal-datatable tbody').on('click', 'tr', function(){
        event.stopPropagation();
        var $target = $(event.target);
        if ( $target.closest("td").attr("colspan") > 1 ) {
            $target.slideUp();
        } else {
            $target.closest("tr").next().find("p").slideToggle();
        }
    });
});