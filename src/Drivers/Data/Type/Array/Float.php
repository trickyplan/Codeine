<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (float) $Value;

        return $Call['Value'];
    });

    setFn(['Read','Where'], function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (float) $Value;

        return $Call['Value'];
    });