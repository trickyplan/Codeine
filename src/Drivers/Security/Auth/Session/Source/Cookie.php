<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Read', function ($Call)
    {
        if (PHP_SAPI == 'cli')
            return '1';

        return isset($_COOKIE['SID']) ? $_COOKIE['SID']: null;
    });

    setFn ('Write', function ($Call)
    {
        setcookie ('SID', $Call['Session']['ID'], time()+84600*180, '/', null, $Call['Secure'], $Call['HTTP Only']); // OPTME!
        $_COOKIE['SID'] = $Call['Session']['ID'];
        return $Call;
    });


    setFn('Annulate', function ($Call)
    {
        if (isset($_COOKIE['SID']) && !empty($_COOKIE['SID']))
            setcookie ('SID', ''); // OPTME!

        return $Call;
    });