<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
        if (is_scalar($Call['A']) && is_scalar($Call['B']))
            return strtr($Call['A'],',','.')*strtr($Call['B'],',','.');
        else
            return 0;
    });

    setFn('Divide', function ($Call)
    {
        return $Call['A']/$Call['B'];
    });