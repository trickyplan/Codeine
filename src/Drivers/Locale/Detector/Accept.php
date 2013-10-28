<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Detect', function ($Call)
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            preg_match_all (
                '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

            $Locales = array_combine ($Parsed[1], $Parsed[4]);

            foreach ($Locales as $Locale => $Q)
                if ($Q === '') $Locales[$Locale] = 1;

            arsort ($Locales, SORT_NUMERIC);

            foreach ($Locales as $Locale => $Quality)
            {
                if (isset($Call['Locales']['Map'][$Locale]))
                {
                    $Call['Locale'] = $Call['Locales']['Map'][$Locale];
                    break;
                }
            }
        }

        F::Log('Accept-Language suggest locale *'.$Call['Locale'].'*', LOG_INFO);

        return $Call;
    });