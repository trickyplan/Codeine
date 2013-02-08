<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    setFn('Determine', function ($Call)
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

    setFn('Widget', function ($Call)
    {
        if (!isset($Call['Session']['City']))
            $Call['Session']['City'] = F::Run(null, 'Determine', $Call);

        $Cities = F::Run('Entity', 'Read', ['Entity' => 'City']);

        foreach ($Cities as $City)
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'City',
                    'ID' => 'Show/Widget',
                    'Data' => $City
                );

        return $Call;
    });

    setFn('ChangeIfSearch', function ($Call)
    {
        if (isset($Call['Request']['Filter']['City']))
        {
            F::Run('Security.Auth', 'Write', $Call, ['Data' => ['City' => $Call['Request']['Filter']['City']]]);
            $Call['Session']['City'] = $Call['Request']['Filter']['City'];
        }

        return $Call;
    });