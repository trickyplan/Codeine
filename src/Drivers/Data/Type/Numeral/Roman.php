<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });