<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Add', function ($Call)
    {
        return bcadd($Call['A'],$Call['B']);
    });

    self::setFn('Substract', function ($Call)
    {
        return bcsub($Call['A'],$Call['B']);
    });

    self::setFn('Multiply', function ($Call)
    {
        return bcmul($Call['A'],$Call['B']);
    });

    self::setFn('Divide', function ($Call)
    {
        return bcdiv($Call['A'],$Call['B']);
    });