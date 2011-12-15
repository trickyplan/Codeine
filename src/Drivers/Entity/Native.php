<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Create', function ($Call)
    {
        return F::Run('Engine.IO', 'Write', $Call);
    });

    self::setFn ('Read', function ($Call)
    {

        return $Call;
    });

    self::setFn ('Update', function ($Call)
    {

        return $Call;
    });

    self::setFn ('Delete', function ($Call)
    {

        return $Call;
    });

