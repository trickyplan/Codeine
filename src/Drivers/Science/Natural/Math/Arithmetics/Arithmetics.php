<?php

    /* Codeine
     * @author BreathLess
     * @description: Default driver for standart calculations
     * @package Codeine
     * @version 6.0
     * @date 28.10.10
     * @time 1:43
     */

    self::Fn('Add', function ($Call)
    {
        return $Call['A']+$Call['B'];
    });

    self::Fn('Substract', function ($Call)
    {
        return $Call['A']-$Call['B'];
    });

    self::Fn('Multiply', function ($Call)
    {
        return $Call['A']*$Call['B'];
    });
    
    self::Fn('Divide', function ($Call)
    {
        return $Call['A']/$Call['B'];
    });
