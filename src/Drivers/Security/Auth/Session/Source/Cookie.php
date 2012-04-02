<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Read', function ($Call)
    {
        return isset($_COOKIE['SID']) ? $_COOKIE['SID']: null;
    });

    self::setFn ('Write', function ($Call)
    {
        return setcookie ('SID', $Call['SID'], (time() + 187600), '/', null, false, true); // OPTME!
    });


    self::setFn('Annulate', function ($Call)
    {
        setcookie ('SID', ''); // OPTME!

        return $Call;
    });