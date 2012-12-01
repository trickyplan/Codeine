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
            return 'CLI';

        return isset($_COOKIE['SID']) ? $_COOKIE['SID']: null;
    });

    setFn ('Write', function ($Call)
    {
        setcookie ('SID', $Call['Session']['ID'], 2147483648, '/', null, $Call['Secure'], $Call['HTTP Only']); // OPTME!
        $_COOKIE['SID'] = $Call['Session']['ID'];
        return $Call;
    });


    setFn('Annulate', function ($Call)
    {
        setcookie ('SID', ''); // OPTME!

        return $Call;
    });