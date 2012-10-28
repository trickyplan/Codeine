<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Add', function ($Call)
    {
        return gmp_add($Call['A'],$Call['B']);
    });

    self::setFn('Substract', function ($Call)
    {
        return gmp_sub($Call['A'],$Call['B']);
    });

    self::setFn('Multiply', function ($Call)
    {
        return gmp_mul($Call['A'],$Call['B']);
    });

    self::setFn('Divide', function ($Call)
    {
        return gmp_div($Call['A'],$Call['B']);
    });