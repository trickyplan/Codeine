<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $GeoIP = F::Run('System.GeoIP', 'LatLon', ['Value' => F::Run('System.Interface.Web.IP', 'Get')]);

        if (isset($GeoIP['Lat']))
        {
            $Call['Session']['Lat'] = $GeoIP['Lat'];
            $Call['Session']['Long'] = $GeoIP['Lon'];

            $Cities = F::Run('Entity', 'Read', array('Entity' => 'City'));

            $Sorted = array();

            foreach ($Cities as $City)
                $Sorted[$City['ID']] = F::Run('Science.Geography.Distance', 'Calc', array('From' => $Call['Session'], 'To' => $City));

            asort($Sorted);
            list($DeterminedCity) = each($Sorted);
            F::Log('City determined', LOG_INFO);
        }
        else
            $DeterminedCity = 1;

        return $DeterminedCity;
    });