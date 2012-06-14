<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Identificate', function ($Call)
    {

    });

    self::setFn('Authenticate', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['token']);
        return $Call;
    });