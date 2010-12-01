<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: BC version of standart operations
     * @package Calculate
     * @subpackage Standart
     * @version 5.0
     * @date 28.10.10
     * @time 1:46
     */

    $Add = function ($Call)
    {
        return bcadd($Call['A'],$Call['B']);
    };

    $Substract = function ($Call)
    {
        return bcsub($Call['A'], $Call['B']);
    };

    $Multiply = function ($Call)
    {
        return bcmul($Call['A'],$Call['B']);
    };

    $Divide = function ($Call)
    {
        return bcdiv($Call['A'],$Call['B']);
    };

    self::Fn('SquareRoot', function ($Call)
    {
        return bcsqrt($Call['Input']);
    });
