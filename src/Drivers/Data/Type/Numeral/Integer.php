<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return (int) $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        return (int) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return rand();
    });