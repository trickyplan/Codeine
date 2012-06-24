<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Write', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });