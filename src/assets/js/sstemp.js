/****************SSTemp JS Plugin****************/

function drawChartSSTemp()
{
    var data = google.visualization.arrayToDataTable(sstemp_data);

    var options = {

        title: 'Température par heure sur les 7 derniers jours',

        curveType: 'function',

        legend: { position: 'bottom' }

    };

    var chart = new google.visualization.LineChart(document.getElementById('sstemp_chart'));

    chart.draw(data, options);
}

$(function () {
    e.channel('SSTemp-channel')
        .listen('.Tchoblond59\\SSTemp\\Events\\SSTempEvent', function (e) {
            console.log('SSTempEvent', e);
            if(e.type=="temp")
                $('.card-figures .figures[data-sensorid='+e.sensor.id+']').animate({'opacity': 0}, 1000, function () {
                    $('.card-figures .figures[data-sensorid='+e.sensor.id+']').text(e.value+'°');
                }).animate({'opacity': 1}, 1000);
            else if(e.type=="hum")
                $('.card-figures .figures[data-sensorid='+e.sensor.id+']').animate({'opacity': 0}, 1000, function () {
                    $('.card-figures .figures[data-sensorid='+e.sensor.id+']').text(e.value);
                }).animate({'opacity': 1}, 1000);
        })

    if($('#sstemp_chart').length)
    {
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChartSSTemp);
    }
});
