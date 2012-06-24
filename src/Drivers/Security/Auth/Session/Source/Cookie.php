<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Read', function ($Call)
    {
        return isset($_COOKIE['SID']) ? $_COOKIE['SID']: null;
    });

    self::setFn ('Write', function ($Call)
    {
        setcookie ('SID', $Call['Session']['ID'], (time() + $Call['TTL']), '/', null, $Call['Secure'], $Call['HTTP Only']); // OPTME!
        return $Call;
    });


    self::setFn('Annulate', function ($Call)
    {
        setcookie ('SID', ''); // OPTME!

        return $Call;
    });