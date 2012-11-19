<?php

    /* Codeine
     * @author BreathLess
     * @description Date() engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Format', function ($Call)
    {
        if (isset($Call['Value']))
        {
            if ($Call['Format'] == 'ISO8601')
                $Call['Format'] = DATE_ISO8601;
            return date($Call['Format'], (int) $Call['Value']);
        }
        else
            return date($Call['Format']);
    });