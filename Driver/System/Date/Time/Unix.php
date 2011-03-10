<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Unix date wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 09.03.11
     * @time 16:47
     */

    self::Fn('Get', function ($Call)
    {
        return passthru('date +%s');
    });
