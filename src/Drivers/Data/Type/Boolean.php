<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });