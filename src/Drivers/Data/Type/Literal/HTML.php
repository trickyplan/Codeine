<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return $Call['Value']; // FIXME
    });

    setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });