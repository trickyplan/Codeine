<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (is_numeric($Call['Value']))
            $Call['Value'] = (float) $Call['Value'];

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        if (is_numeric($Call['Value']))
            $Call['Value'] = (float) $Call['Value'];

        return $Call['Value'];
    });