/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

google.charts.load('current', {packages: ['bar']});
google.charts.setOnLoadCallback(drawMultSeries);

function drawMultSeries(){

    $.getJSON("getArray", function(data){
        var data = google.visualization.arrayToDataTable([
            ['Onderwerp', 'Laatste nieuwe scores per onderwerp'],
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
        var options = {
            chart:{
                title: 'Algemene score'
            },
            bars: 'horizontal',
            chartArea: {width: '65%'}, //This is the width of the bar chart inside its div
            colors: ['#cddc39'],
            hAxis: {
                title: 'Score (%)',
                viewWindow: {
                    max: 100,
                    min: 0
                }
            },
            vAxis: {
                title: 'Onderwerp'
            }
        };
    
        var chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        //chart.draw(data, options);
    });
    
}