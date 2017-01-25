/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

google.charts.load('current', {packages: ['bar']});
google.charts.setOnLoadCallback(drawMultSeries);

var chart_data;
var options;
var chart;
/*
window.onload = resize();
window.onresize = resize();


function resize(){
    var chart = new google.charts.Bar(document.getElementById('chart_div'));
    chart.draw(chart_data, google.charts.Bar.convertOptions(options));

}
*/

$(window).resize(function(){
    if(this.resizeTO) clearTimeout(this.resizeTO);
    this.resizeTO = setTimeout(function(){
        $(this).trigger('resizeEnd');
    }, 500);
});

$(window).on('resizeEnd', function(){
    chart.draw(chart_data, google.charts.Bar.convertOptions(options));
});


function drawMultSeries(){
    
    $.getJSON("getArray", function(data){
        chart_data = google.visualization.arrayToDataTable([
            ["", data[12].Score],
            [data[1].Topic, data[1].Score],
            [data[2].Topic, data[2].Score],
            [data[3].Topic, data[3].Score],
            [data[4].Topic, data[4].Score],
            [data[5].Topic, data[5].Score],
            [data[6].Topic, data[6].Score],
            [data[7].Topic, data[7].Score],
            [data[8].Topic, data[8].Score],
            [data[9].Topic, data[9].Score],
            [data[10].Topic, data[10].Score],
            [data[11].Topic, data[11].Score]
        ]);
        /*
        chart_data.addColumn('string', "");
        chart_data.addColumn('number', data[12].Score);
        for(var i = 0; i < data.length - 1; i++){
            chart_data.addRow([data[i].Topic, parseInt(data[i].Score)]);
        }
        */
    
        options = {
            bars: 'horizontal',
            chartArea: {width: '100%', height: '100%'}, //This is the width of the bar chart inside its div
            colors: ['lightgray'],
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
                ticks: [0, 25, 50, 75, 100]
            }
        };
        //resize();
        chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(chart_data, google.charts.Bar.convertOptions(options));
    });
}