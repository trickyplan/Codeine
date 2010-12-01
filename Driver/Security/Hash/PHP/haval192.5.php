<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: haval192,5 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('haval192,5', $Call['Input'], $Call['Key']);
        else
            return hash('haval192,5', $Call['Input']);
    });
