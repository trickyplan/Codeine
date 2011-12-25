<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Root', function ($Call)
    {
        $Call['Value'] = '<h1>Codeine 7 works!</h1>';

        return $Call;
    });