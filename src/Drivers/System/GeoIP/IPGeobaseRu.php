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
            'Where' => $Call['Prefix'].$Call['IP'].$Call['Postfix']
        ])[0]);
    });

    setFn('LatLon', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return ['Lat' => $Response->ip->lat, 'Lon' => $Response->ip->lng];
    });

    setFn('Country', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return (string) $Response->ip->country;
    });

    setFn('Region', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return (string) $Response->ip->region;
    });

    setFn('City', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return (isset($Response->ip->city)? (string) $Response->ip->city: (string) $Response->ip->country);
    });
