<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: salsa20 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('salsa20', $Call['Input'], $Call['Key']);
        else
            return hash('salsa20', $Call['Input']);
    });
