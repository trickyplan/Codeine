<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Run', function ($Call)
    {
        ob_start();
        // $Call['Server'] = $_SERVER;
        $Call['Request'] = $_REQUEST;

        $Call['Value'] = $_SERVER['REQUEST_URI'];

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