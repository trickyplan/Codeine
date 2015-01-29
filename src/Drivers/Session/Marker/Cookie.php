<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Read', function ($Call)
    {
        if (PHP_SAPI == 'cli')
            return '1';

        return isset($Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]) ? $Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]: null;
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

            $Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']] = $Call['SID'];
        else
            $Call = F::Hook('Cookie.Set.Failed', $Call);

        return $Call;
    });


    setFn('Destroy', function ($Call)
    {
        if (isset($Call['HTTP']['Cookie'][$Call['Marker']['Cookie']['Name']]))
            setcookie ($Call['Marker']['Cookie']['Name'], ''); // OPTME!

        return $Call;
    });