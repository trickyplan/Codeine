<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Load', function ($Call)
    {
        return simplexml_load_string(F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Where' => $Call['Prefix'].$Call['Value'].$Call['Postfix']
        ])[0]);
    });

    self::setFn('LatLon', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return ['Lat' => $Response->ip->lat, 'Lon' => $Response->ip->lng];
    });

    self::setFn('Country', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return (string) $Response->ip->country;
    });

    self::setFn('Region', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);
        return (string) $Response->ip->region;
    });

    self::setFn('City', function ($Call)
    {
        $Response = F::Run(null, 'Load', $Call);

        return isset($Response->ip->city)? $Response->ip->city: $Response->ip->country;
    });
