<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return F::Run('Security.Auth.System.Password', 'Challenge', $Call);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });