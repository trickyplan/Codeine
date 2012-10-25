<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('LatLon', function ($Call)
    {
        $Record = geoip_record_by_name($Call['Value']);
        return [
            'lat' => $Record['latitude'],
            'lon' => $Record['longitude']
        ];
    });

    self::setFn('Country', function ($Call)
    {
        return geoip_country_code_by_name($Call['Value']);
    });

    self::setFn('City', function ($Call)
    {
        return geoip_record_by_name($Call['Value'])['city'];
    });

    self::setFn('Region', function ($Call)
    {
        return geoip_region_by_name($Call['Value']);
    });
