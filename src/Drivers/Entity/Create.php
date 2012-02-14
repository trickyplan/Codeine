<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run('Entity.Create', $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        // TODO Realize GET

        // TODO Form Generator

        // TODO Form Parser


        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        // TODO Realize POST

        // TODO Validation
        F::Run('Engine.Entity', 'Create', $Call);

        return $Call;
    });