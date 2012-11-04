<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });