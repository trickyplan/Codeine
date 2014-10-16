<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Eq', function ($Call)
    {
        return ($Call['A'] == $Call['B']);
    });

    setFn('NotEq', function ($Call)
    {
        return ($Call['A'] != $Call['B']);
    });

    setFn('Lt', function ($Call)
    {
        return $Call['A'] < $Call['B'];
    });

    setFn('Gt', function ($Call)
    {
        return !$Call['A'] > $Call['B'];
    });