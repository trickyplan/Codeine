<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tiger128,3 Hash Extension Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('tiger128,3', $Call['Input'], $Call['Key']);
        else
            return hash('tiger128,3', $Call['Input']);
    });
