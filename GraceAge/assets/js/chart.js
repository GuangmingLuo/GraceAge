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
            ['Eten', data.Food],
            ['Veiligheid', data.Safety],
            ['Comfort', data.Comfort],
            ['Autonomie', data.Autonomy],
            ['Respect', data.Respect],
            ['Staf Respons', data.StaffResponse],
            ['Staf Bonding', data.StaffBonding],
            ['Activiteiten', data.Activities],
            ['Relaties', data.Relationships],
            ['Andere', data.Other]
        ]);
        var options = {
            chart:{
                title: 'Algemene score'
            },
            bars: 'horizontal',
            chartArea: {width: '65%'}, //This is the width of the bar chart inside its div
            colors: ['#7a993c'],
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