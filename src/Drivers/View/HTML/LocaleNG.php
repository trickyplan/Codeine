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

        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            $Locales = [];

            foreach ($Pockets[1] as $IX => $Match)
            {
                list($Locale, $Token) = explode(':', $Match);

                $Slices = explode('.', trim($Token));

                if (!isset($Locales[$Locale]))
                {
                    $LocaleParts = explode('.', $Locale);
                    $ID = array_pop($LocaleParts);
                    $Asset = implode('.', $LocaleParts);

                    $Asset = strtr($Asset, '.', '/');

                    $NewLocales = F::Run('IO', 'Read',
                        array (
                              'Storage' => 'Locale',
                              'Scope'   => $Asset.'/Locale/'.$Call['Language'],
                              'Where'   => $ID
                        ));

                    $Locales[$Locale] = [];

                    $NewLocales = array_reverse($NewLocales);

                    if (is_array($NewLocales))
                        foreach ($NewLocales as $NewLocale)
                            $Locales[$Locale] = F::Merge($Locales[$Locale], $NewLocale);
                    else
                        F::Log('Locale '.$Locale.' not loaded', LOG_ERR);
                }

                $szSlices = sizeof($Slices);

                $TrueMatch = false;

                for ($ic = $szSlices; $ic > 0; --$ic) // TODO Абстрагировать
                    if ($Replace = F::Dot($Locales[$Locale], $cMatch = implode('.', array_slice($Slices, 0, $ic))))
                    {
                        if (!is_array($Replace))
                            $TrueMatch = $cMatch;
                        break;
                    }

                if ($TrueMatch)
                    $Call['Output'] = str_replace($Pockets[0][$IX], $Replace, $Call['Output']);
                else
                    $Call['Output'] = str_replace($Pockets[0][$IX], '<span class="nl">' . $Match . '</span>', $Call['Output']);
            }
        }

        return $Call;
    });
