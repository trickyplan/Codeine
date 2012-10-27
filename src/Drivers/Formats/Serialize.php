<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Decode', function ($Call)
    {
        return unserialize($Call['Value']);
    });

    self::setFn('Encode', function ($Call)
    {
        return serialize($Call['Value']);
    });