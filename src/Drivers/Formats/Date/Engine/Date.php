<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Format', function ($Call)
    {
        if (isset($Call['Value']))
            return date($Call['Format'], $Call['Value']);
        else
            return date($Call['Format']);
    });