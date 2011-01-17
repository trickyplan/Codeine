<?php

function F_Geo_Render($Args)
{
    View::JSFile('~jQuery/Plugins/Map.js');
    $Element = "<div id='Map' style='width: 80%; height: 300px;'></div>
        <jsrun>
        var Lat = $('#Geo_Latitude').val();
        if (Lat == '')
            Lat = 0;
                
        var Lon = $('#Geo_Longitude').val();

        if (Lon == '')
            Lon = 0;
                
        $('#Map').googleMaps({
                labels: true, 
                controls: true,
                zoom: 8,
                latitude: Lat,
                longitude: Lon
            });
        GEvent.addListener($.googleMaps.gMap,'click',
        function(overlay, latlng) { $('#Geo_Latitude').val(latlng.y);$('#Geo_Longitude').val(latlng.x);});
        </jsrun>";
    
    return ''; $Element;
}