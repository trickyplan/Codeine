<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Value']) && !empty($Call['Value']) && $Call['Value']>0)
        {
            if (!is_numeric($Call['Value']))
            {
                $Date = date_parse_from_format('d.m.Y', $Call['Value']);
                return mktime(3,0,0, $Date['month'], $Date['day'], $Date['year']);
            }
            else
                return $Call['Value'];
        }
        else
            return null;

    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value']; // Separate Where?
    });

    setFn('Populate', function ($Call)
    {
        return rand(0, time());
    });