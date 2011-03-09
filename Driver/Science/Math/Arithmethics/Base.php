<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Default driver for standart calculations
     * @package Calculate
     * @subpackage Standart
     * @version 5.0
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

     self::Fn('SquareRoot', function ($Call)
    {
        return sqrt($Call['Input']);
    });
