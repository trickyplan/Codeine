<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: PHP Mail driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 4:35
     */

    self::Fn('Connect', function ($Call)
    {
        return true;
    });

    self::Fn('Send', function ($Call)
    {
        return mb_send_mail($Call['Call']['To'], $Call['Call']['Subject'],$Call['Call']['Message']);
    });