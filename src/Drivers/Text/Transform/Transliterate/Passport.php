<?php

    /* Codeine
     * @author BreathLess
     * @description Транслитерация по правилам загранпаспортов 
     * @package Codeine
     * @version 7.0
     */

    self::setFn('2English', function ($Call)
    {
        $Call['Value'] = strtr($Call['Value'], $Call['Map']);

        return $Call['Value'];
    });