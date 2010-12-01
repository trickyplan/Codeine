<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: snefru256 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('snefru256', $Call['Input'], $Call['Key']);
        else
            return hash('snefru256', $Call['Input']);
    });
