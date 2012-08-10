<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Write', function ($Call)
    {
        return (int) $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return (int) $Call['Value'];
    });

    self::setFn('Populate', function ($Call)
    {
        return rand();
    });