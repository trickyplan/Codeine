<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('LatLon', function ($Call)
    {
        $Record = geoip_record_by_name($Call['HTTP']['IP']);

        return [
                   'lat' => $Record['latitude'],
                   'lon' => $Record['longitude']
               ];
    });

    setFn('Country', function ($Call)
    {
        return geoip_country_code_by_name($Call['HTTP']['IP']);
    });

    setFn('City', function ($Call)
    {
        return geoip_record_by_name($Call['HTTP']['IP'])['city'];
    });

    setFn('Region', function ($Call)
    {
        return geoip_region_by_name($Call['HTTP']['IP']);
    });
