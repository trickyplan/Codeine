<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Language']))
            $Call['Language'] = F::Live($Call['Language']);
        else
            $Call['Language'] = $Call['Languages']['Default'];

        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            $Locales = [];

            foreach ($Pockets[1] as $IX => $Match)
            {
                if (strpos($Match, ':') !== false)
                    list($Locale, $Token) = explode(':', $Match);
                else
                {
                    $Locale = 'Locale';
                    $Token = $Match;
                }

                if (!isset($Locales[$Locale]))
                {
                    $LocaleParts = explode('.', $Locale);
                    $ID = array_pop($LocaleParts);
                    $Asset = implode('.', $LocaleParts);

                    $Asset = strtr($Asset, '.', '/');

                    $NewLocales = F::Run('IO', 'Read',
                        [
                              'Storage' => 'Locale',
                              'Scope'   => $Asset.'/Locale/'.$Call['Language'],
                              'Where'   => $ID
                        ]);

                    $Locales[$Locale] = [];

                    if (is_array($NewLocales))
                    {
                        $NewLocales = array_reverse($NewLocales);
                        foreach ($NewLocales as $NewLocale)
                            $Locales[$Locale] = F::Merge($Locales[$Locale], $NewLocale);
                    }
                    else
                        F::Log('Locale '.$Locale.' not loaded', LOG_ERR);
                }

                if (($Replace = F::Dot($Locales[$Locale], $Token)) !== null)
                {
                    if (is_scalar($Replace))
                        $Call['Output'] = str_replace($Pockets[0][$IX], $Replace, $Call['Output']);
                }
                else
                    $Call['Output'] = str_replace($Pockets[0][$IX], '<span class="nl">' . $Match . '</span>', $Call['Output']);
            }
        }

        return $Call;
    });
