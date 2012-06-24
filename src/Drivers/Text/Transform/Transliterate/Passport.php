<?php

    /* Codeine
     * @author BreathLess
     * @description Транслитерация по правилам загранпаспортов 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('2English', function ($Call)
    {
        $Call['Value'] = strtr($Call['Value'], $Call['Map']);

        return $Call['Value'];
    });