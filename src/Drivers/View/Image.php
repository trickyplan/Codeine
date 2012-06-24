<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Process', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'image/png'; // FIXME
        $Call['Output'] = $Call['Image'];

        return $Call;
    });