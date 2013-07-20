<?php

    /* Codeine
     * @author BreathLess
     * @description Транслитерация по правилам загранпаспортов 
     * @package Codeine
     * @version 7.x
     */

    setFn('2English', function ($Call)
    {
        $Call['Value'] = strtr($Call['Value'], $Call['Map']);
        return $Call['Value'];
    });

    setFn('2Russian', function ($Call)
    {
        $Call['Value'] = strtr($Call['Value'], $Call['Reverse Map']);
        return $Call['Value'];
    });