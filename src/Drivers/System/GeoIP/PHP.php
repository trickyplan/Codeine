<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('LatLon', function ($Call)
    {
        $Record = geoip_record_by_name($Call['Value']);
        return [
            'lat' => $Record['latitude'],
            'lon' => $Record['longitude']
        ];
    });

    setFn('Country', function ($Call)
    {
        return geoip_country_code_by_name($Call['Value']);
    });

    setFn('City', function ($Call)
    {
        return geoip_record_by_name($Call['Value'])['city'];
    });

    setFn('Region', function ($Call)
    {
        return geoip_region_by_name($Call['Value']);
    });
