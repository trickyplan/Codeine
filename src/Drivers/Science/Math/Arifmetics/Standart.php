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
        return strtr($Call['A'],',','.')*strtr($Call['B'],',','.');
    });

    self::setFn('Divide', function ($Call)
    {
        return $Call['A']/$Call['B'];
    });