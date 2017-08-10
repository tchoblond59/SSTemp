/****************SSTemp JS Plugin****************/
$(function () {
    e.channel('SSTemp-channel')
        .listen('.Tchoblond59.SSTemp.Events.SSTempEvent', function (e) {
            console.log('SSTempEvent', e)
            if(e.type=="temp")
                $('.card-figures .figures[data-sensorid='+e.sensor.id+']').animate({'opacity': 0}, 1000, function () {
                    $('.card-figures .figures[data-sensorid='+e.sensor.id+']').text(e.value+'°');
                }).animate({'opacity': 1}, 1000);
            else if(e.type=="hum")
                $('.card-figures .figures[data-sensorid='+e.sensor.id+']').animate({'opacity': 0}, 1000, function () {
                    $('.card-figures .figures[data-sensorid='+e.sensor.id+']').text(e.value);
                }).animate({'opacity': 1}, 1000);
        })
})
