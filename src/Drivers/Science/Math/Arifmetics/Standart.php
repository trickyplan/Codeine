<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Add', function ($Call)
    {
        return $Call['A']+$Call['B'];
    });

    setFn('Substract', function ($Call)
    {
        return $Call['A']-$Call['B'];
    });

    setFn('Multiply', function ($Call)
    {
        return strtr($Call['A'],',','.')*strtr($Call['B'],',','.');
    });

    setFn('Divide', function ($Call)
    {
        return $Call['A']/$Call['B'];
    });