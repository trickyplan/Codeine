<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: microtime() wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 09.03.11
     * @time 16:34
     */

    self::Fn('Get', function ($Call)
    {
        return microtime(true);
    });
