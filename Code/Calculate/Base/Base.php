<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Default driver for standart calculations
     * @package Calculate
     * @subpackage Standart
     * @version 0.1
     * @date 28.10.10
     * @time 1:43
     */

    $Add = function ($Call)
    {
        return $Call['A']+$Call['B'];
    };

    $Substract = function ($Call)
    {
        return $Call['A']-$Call['B'];
    };

    $Multiply = function ($Call)
    {
        return $Call['A']*$Call['B'];
    };

    $Divide = function ($Call)
    {
        return $Call['A']/$Call['B'];
    };


    self::Fn('SquareRoot', function ($Call)
    {
        return sqrt($Call['Input']);
    });
