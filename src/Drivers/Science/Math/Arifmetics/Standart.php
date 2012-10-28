<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Add', function ($Call)
    {
        return $Call['A']+$Call['B'];
    });

    self::setFn('Substract', function ($Call)
    {
        return $Call['A']-$Call['B'];
    });

    self::setFn('Multiply', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['B']);
        return $Call['A']*(float)$Call['B'];
    });

    self::setFn('Divide', function ($Call)
    {
        return $Call['A']/$Call['B'];
    });