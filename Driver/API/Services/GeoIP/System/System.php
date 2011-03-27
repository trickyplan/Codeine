<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: System geoip wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 5:37
     */

    self::Fn('GetCountry3', function ($Call)
    {
        return geoip_country_code3_by_name($Call['IP']);
    });

    self::Fn('GetCountry2', function ($Call)
    {
        return geoip_country_code_by_name($Call['IP']);
    });

    self::Fn(array('Get','GetCountry'), function ($Call)
    {
        return geoip_country_name_by_name($Call['IP']);
    });
