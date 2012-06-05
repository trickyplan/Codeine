<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Eq', function ($Call)
    {
        return ($Call['A'] == $Call['B']);
    });

    self::setFn('NotEq', function ($Call)
    {
        return ($Call['A'] != $Call['B']);
    });

    self::setFn('Lt', function ($Call)
    {
        return $Call['A'] < $Call['B'];
    });

    self::setFn('Gt', function ($Call)
    {
        return !$Call['A'] > $Call['B'];
    });