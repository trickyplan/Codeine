<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Process', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'image/png'; // FIXME
        $Call['Output'] = $Call['Image'];

        return $Call;
    });