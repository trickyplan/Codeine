<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Input', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Output', function ($Call)
    {
        return $Call['Value'];
    });