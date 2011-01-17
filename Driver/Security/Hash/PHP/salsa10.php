<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: salsa10 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('salsa10', $Call['Input'], $Call['Key']);
        else
            return hash('salsa10', $Call['Input']);
    });
