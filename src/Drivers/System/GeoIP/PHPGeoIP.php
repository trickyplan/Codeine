<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Country', function ($Call)
    {
        return geoip_country_code_by_name($Call['Value']);
    });

    self::setFn('Region', function ($Call)
    {
        return geoip_region_by_name($Call['Value']);
    });
