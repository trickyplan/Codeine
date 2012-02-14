<?php

/* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Process', function ($Call)
    {
        $Call['Headers']['Content-type'] = 'image/png';
        $Call['Output'] = $Call['Image'];

        return $Call;
    });