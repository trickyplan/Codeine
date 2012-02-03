<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('CountryAndRegion', function ($Call)
    {
        return geoip_time_zone_by_country_and_region($Call['Country'], $Call['Region']);
    });
