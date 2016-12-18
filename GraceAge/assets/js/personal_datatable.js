/*
 * Automatically sorts on name
 * Default shows first 10 entries
 * Allows to search on name.
 */

google.charts.load('current', {packages: ['bar']});
google.charts.setOnLoadCallback(initialize());


$(document).ready(function(){
    //Makes the datatable
    var table = $('#personal-datatable').DataTable();
    //$('#modChart').on('shown.bs.modal', initialize);
    //initialize();
    });
    
function initialize(){
    //When clicked on a table row, this function is called, which opens the dialog screen and draws the chart.
     $('#personal-datatable tbody tr').on('click', function(){
     //$('.showchart').on('click', function(){
         drawChart();
     });
}
    
function drawChart(){
    $.getJSON("getPersonalScores", function(data){
        var chart_data = google.visualization.arrayToDataTable([
    ]);
    chart_data.addColumn('string', "");
    chart_data.addColumn('number', "Score");
    for(var i = 0; i < data.length; i++){
        chart_data.addRow([data[i].Topic, parseInt(data[i].Score)]);
    }

     var options = {
            bars: 'horizontal',
            width: 800, height: 450,
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
    });
}
