<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tiger192,4 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('tiger192,4', $Call['Input'], $Call['Key']);
        else
            return hash('tiger192,4', $Call['Input']);
    });
