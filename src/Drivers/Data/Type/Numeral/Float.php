<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
            return (float) strtr($Call['Value'], ',','.');
        else
            return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        if (is_scalar($Call['Value']))
            return (float) strtr($Call['Value'], ',','.');
        else
            return $Call['Value'];
    });