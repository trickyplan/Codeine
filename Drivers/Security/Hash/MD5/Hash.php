<?php

    /* Codeine
     * @author BreathLess
     * @description: md5 Hash Extension Wrapper
     * @package Codeine
     * @version 6.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('md5', $Call['Value'], $Call['Key']);
        else
            return hash('md5', $Call['Value']);
    });
