<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
                if (isset($Call['Accept-Language'][$Locale]))
                {
                    $Call['Locale'] = $Call['Accept-Language'][$Locale];
                    break;
                }
            }

            if (isset($Call['Locale']))
                F::Log('Accept-Language suggest locale *'.$Call['Locale'].'*', LOG_INFO + 0.5);
        }

        if (isset($Call['HTTP']['Host']) && isset($Call['Locales']['Hosts']))
            foreach ($Call['Locales']['Hosts'] as $Host => $Locale)
                if (preg_match('/'.$Host.'/', $Call['HTTP']['Host']))
                {
                    $Call['Locale'] = $Locale;
                    F::Log('Hosts suggest locale *'
                        .$Call['Locale']
                        .'* by regex *'.$Host.'*', LOG_INFO + 0.5);
                    break;
                }

        return $Call;
    });