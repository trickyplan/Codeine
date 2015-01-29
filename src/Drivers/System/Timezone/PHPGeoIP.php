<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('CountryAndRegion', function ($Call)
    {
        return geoip_time_zone_by_country_and_region($Call['Country'], $Call['Region']);
    });
