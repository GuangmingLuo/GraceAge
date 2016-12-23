/*
 * Automatically sorts on name
 * Default shows first 10 entries
 * Allows to search on name.
 */

google.charts.load('current', {packages: ['bar'], "callback": initialize});
//google.charts.setOnLoadCallback(initialize());

var id;

$(document).ready(function(){
    //Makes the datatable
    var table = $('#personal-datatable').DataTable({
        "pagingType": "simple",    //Shows "Previous" & "Next" buttons.
        "language": {
            "search": "",   //No text before the search field.
            "searchPlaceholder": "Find patient" //Placeholder to indicate what to type
        },
        "dom": '<lf<t>ip>', //Puts search box underneath the "Showing elements".
        //Only search on the first column.
        "aoColumnDefs": [
            {"bSearchable": false, "aTargets": [1,2,3]}
        ]
    });
    id = 2;
    initialize();
    //$('#modChart').on('shown.bs.modal', drawChart);
    });
    
    
function setID(new_id){
    id = new_id;
}

function setBarChartTitle(new_title, new_subtitle){
    document.getElementById("exampleModalLabel").innerHTML = new_title;
    document.getElementById("modalSubtitle").innerHTML = new_subtitle;
}
    
function initialize(){
    //When clicked on a table row, this function is called, which opens the dialog screen and draws the chart.
    $('#personal-datatable tbody tr').on('click', function(){
     //$('.showchart').on('click', function(){
        drawChart();
    });
}
    
function drawChart(){
    var request = $.ajax({
        method: "POST",
        url: "getPersonalScores",
        data: {id: id},
        dataType: "json"
    })
    
    request.done(function(data){
        var chart_data = google.visualization.arrayToDataTable([]);
        chart_data.addColumn('string', "");
        chart_data.addColumn('number', "Score");
        for(var i = 0; i < data.length; i++){
            chart_data.addRow([data[i].Topic, parseInt(data[i].Score)]);
        }

         var options = {
                bars: 'horizontal',
                width: 800, height: 400,
                //chartArea: {width: '100%', height: '100%'},
                chartArea: {left: '8%', top: '8%', width: '80%', height: '80%'},
                colors: ['#cddc39'],
                legend: {
                    position: 'none'
                },
                vAxis: {
                    textPosition: 'none'
                },
                hAxis: {
                    textPosition: 'none',
                    viewWindow: {
                        max: 100
                    },
                }
            };
         var chart = new google.charts.Bar(document.getElementById('canvas'));
         chart.draw(chart_data, google.charts.Bar.convertOptions(options));
         //chart.draw(chart_data, options);
    });
}
