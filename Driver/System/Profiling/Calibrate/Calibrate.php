<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Ex-Timing Test
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 20:34
     */

    self::Fn('Calibrate', function ($Call)
    {
        return 1/abs(microtime(true)-microtime(true));
    });