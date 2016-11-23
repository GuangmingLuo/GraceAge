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
        ['Privacy', data.Privacy],
        ['Eten', 65.9],
        ['Veiligheid', 61.5],
        ['Comfort', 40],
        ['Autonomie', 29],
        ['Respect', 85],
        ['Staf Respons', 76.9],
        ['Staf Bonding', 20.1],
        ['Activiteiten', 56],
        ['Relaties', 79.6],
        ['Andere', 56]
    ]);
    var options = {
        chart:{
            title: 'Algemene score'
        },
        bars: 'horizontal',
        chartArea: {width: '65%'}, //This is the width of the bar chart inside its div
        hAxis: {
            title: 'Score (%)',
            minValue: 0
        },
        vAxis: {
            title: 'Topic'
        }
    };
    
    var chart = new google.charts.Bar(document.getElementById('chart_div'));
    chart.draw(data, options);
    });
    
}