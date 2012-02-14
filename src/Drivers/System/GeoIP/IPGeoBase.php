<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Load', function ($Call)
    {
        return simplexml_load_string(file_get_contents('http://ipgeobase.ru:7020/geo?ip='.$Call['Value']));
    });

    self::setFn('Country', function ($Call)
    {
        $Response = F::Run('System.GeoIP.IPGeoBase', 'Load', $Call);
        return $Response->ip->country;
    });

    self::setFn('City', function ($Call)
    {
        $Response = F::Run('System.GeoIP.IPGeoBase', 'Load', $Call);
        return isset($Response->ip->city)? $Response->ip->city: $Response->ip->country;
    });
