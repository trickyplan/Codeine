<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: BC version of standart operations
     * @package Calculate
     * @subpackage Standart
     * @version 0.1
     * @date 28.10.10
     * @time 1:46
     */

    $Add = function ($Args)
    {
        return bcadd($Args['A'],$Args['B']);
    };

    $Substract = function ($Args)
    {
        return bcsub($Args['A'], $Args['B']);
    };

    $Multiply = function ($Args)
    {
        return bcmul($Args['A'],$Args['B']);
    };

    $Divide = function ($Args)
    {
        return bcdiv($Args['A'],$Args['B']);
    };

    $SquareRoot = function ($Args)
    {
        return bcsqrt($Args['Value']);
    };