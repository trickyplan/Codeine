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

        return isset($_COOKIE[$Call['Marker']['Cookie']['Name']]) ? $_COOKIE[$Call['Marker']['Cookie']['Name']]: null;
    });

    setFn ('Write', function ($Call)
    {
        if (setcookie ($Call['Marker']['Cookie']['Name'],
            $Call['SID'],
            time() +
            $Call['Marker']['Cookie']['TTL'],
            $Call['Marker']['Cookie']['Path'],
            $Call['Marker']['Cookie']['Domain'],
            $Call['Marker']['Cookie']['Secure'],
            $Call['Marker']['Cookie']['HTTP Only']))

            $_COOKIE[$Call['Marker']['Cookie']['Name']] = $Call['SID'];
        else
            $Call = F::Hook('Cookie.Set.Failed', $Call);

        return $Call;
    });


    setFn('Destroy', function ($Call)
    {
        if (isset($_COOKIE[$Call['Marker']['Cookie']['Name']]))
            setcookie ($Call['Marker']['Cookie']['Name'], ''); // OPTME!

        return $Call;
    });