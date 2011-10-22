<?php

    /* Codeine
     * @author BreathLess
     * @description: sha1 Hash Extension Wrapper
     * @package Codeine
     * @version 6.0
     */

    
    self::Fn('Get', function ($Call)
    {
        if (isset($Call['Key']))
            return hash('sha1', $Call['Value'], $Call['Key']);
        else
            return hash('sha1', $Call['Value']);
    });
