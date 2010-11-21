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

    $Add = function ($Args)
    {
        return $Args['A']+$Args['B'];
    };

    $Substract = function ($Args)
    {
        return $Args['A']-$Args['B'];
    };

    $Multiply = function ($Args)
    {
        return $Args['A']*$Args['B'];
    };

    $Divide = function ($Args)
    {
        return $Args['A']/$Args['B'];
    };

    $SquareRoot = function ($Args)
    {        
        return sqrt($Args['Value']);
    };