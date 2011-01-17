<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: gost Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('gost', $Call['Input'], $Call['Key']);
        else
            return hash('gost', $Call['Input']);
    });
