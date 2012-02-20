<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Run', function ($Call)
    {
        ob_start();
        // $Call['Server'] = $_SERVER;

        if (isset($_FILES))
            $Call['Request'] = F::Merge($_REQUEST, $_FILES);
        else
            $Call['Request'] = $_REQUEST;

        $Call['Run'] = $_SERVER['REQUEST_URI'];

        $Call = F::Run($Call['Service'], $Call['Method'], $Call);

    foreach ($Call['Headers'] as $Key => $Value)
            header ($Key . ' ' . $Value);

        echo $Call['Output'];

        ob_flush ();
        return $Call;
    });

    self::setFn ('User.Agent', function ($Call)
    {
        return $_SERVER['HTTP_USER_AGENT'];
    });

    self::setFn ('User.IP', function ($Call)
    {
        return $_SERVER['REMOTE_ADDR'];
    });

    self::setFn('User.Geo', function ($Call)
    {
        return F::Run('System.GeoIP.PHPGeoIP', 'Country',
            array(
                    'Value' => F::Run('System.Interface.Web', 'User.IP', $Call
                 )));
    });

    self::setFn('User.Time', function ($Call)
    {
        $IP = F::Run('System.Interface.Web', 'User.IP', $Call);

        return F::Run('System.Timezone.PHPGeoIP', 'CountryAndRegion',
            array (
                  'Country' => d(__FILE__, __LINE__, F::Run('System.GeoIP.PHPGeoIP', 'Country', array ('Value' => $IP))),
                  'Region' => d(__FILE__, __LINE__, F::Run('System.GeoIP.PHPGeoIP', 'Region', array ('Value' => $IP)))
            ));
    });

    self::setFn ('DetectUALanguage', function ($Call)
    {
        preg_match_all ('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

        $Languages = array_combine ($Parsed[1], $Parsed[4]);

        foreach ($Languages as $Language => $Q)
            if ($Q === '') $Languages[$Language] = 1;

        arsort ($Languages, SORT_NUMERIC);

        foreach ($Languages as $Language => $Quality)
        {
            if (isset($Call['Language']['Map'][$Language]))
                return $Call['Language']['Map'][$Language];
        }

        return $Call['Language']['Default'];
    });