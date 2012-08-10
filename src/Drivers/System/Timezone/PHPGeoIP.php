<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('CountryAndRegion', function ($Call)
    {
        return geoip_time_zone_by_country_and_region($Call['Country'], $Call['Region']);
    });
