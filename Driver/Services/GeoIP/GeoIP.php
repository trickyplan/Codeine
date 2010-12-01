<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: GeoIP wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 5:37
     */

    self::Fn('GetLocation', function ($Call)
    {
        return geoip_record_by_name($Call['IP']);
    });