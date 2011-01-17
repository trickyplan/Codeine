<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: md5 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('md5', $Call['Input'], $Call['Key']);
        else
            return hash('md5', $Call['Input']);
    });
