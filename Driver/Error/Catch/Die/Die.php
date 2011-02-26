<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Fatal Catcher
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.01.11
     * @time 3:08
     */

    self::Fn('Catch', function ($Call)
    {
        die('Fatal Error'.print_r($Call, true));
    });
