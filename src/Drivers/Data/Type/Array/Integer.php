<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (int) $Value;

        return $Call['Value'];
    });

    setFn(['Read','Where'], function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (int) $Value;

        return $Call['Value'];
    });