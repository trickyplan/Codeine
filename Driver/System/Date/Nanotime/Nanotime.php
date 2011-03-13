<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 09.03.11
     * @time 16:49
     */

    self::Fn('Get', function ($Call)
    {
        return passthru('date +%N');
    });
