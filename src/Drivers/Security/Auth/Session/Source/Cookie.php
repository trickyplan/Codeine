<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Read', function ($Call)
    {
        return isset($_COOKIE['SID']) ? $_COOKIE['SID']: null;
    });

    self::setFn ('Write', function ($Call)
    {
        setcookie ('SID', $Call['Session']['ID'], 2147483648, '/', null, $Call['Secure'], $Call['HTTP Only']); // OPTME!
        return $Call;
    });


    self::setFn('Annulate', function ($Call)
    {
        setcookie ('SID', ''); // OPTME!

        return $Call;
    });