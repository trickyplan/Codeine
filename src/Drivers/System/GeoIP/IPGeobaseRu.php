<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Load', function ($Call)
    {
        return simplexml_load_string(F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Where' => $Call['Prefix'].$Call['HTTP']['IP'].$Call['Postfix']
        ])[0]);
    });

    setFn('LatLon', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        if ($Response)
            return ['Lat' => $Response->ip->lat, 'Lon' => $Response->ip->lng];
        else
            return null;
    });

    setFn('Country', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);

        if ($Response)
            return (string) $Response->ip->country;
        else
            return null;
    });

    setFn('Region', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        if ($Response)
            return (string) $Response->ip->region;
        else
            return null;
    });

    setFn('City', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        if ($Response)
            return (isset($Response->ip->city)? (string) $Response->ip->city: (string) $Response->ip->country);
        else
            return null;
    });
