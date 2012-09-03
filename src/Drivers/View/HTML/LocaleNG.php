<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        $Call['Language'] = F::Live($Call['Language']);

        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            $Pockets[1] = array_unique($Pockets[1]);

            $Locales = [];

            foreach ($Pockets[1] as $IX => $Match)
            {
                $Slices = explode('.', trim($Match));

                if (!isset($Locales[$Slices[0]]))
                {
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Slices[0]));

                    $NewLocales = F::Run('IO', 'Read',
                        array (
                              'Storage' => 'Locale',
                              'Scope'   => $Asset.'/Locale/'.$Call['Language'],
                              'Where'   => $ID
                        ));

                    $Locales[$Slices[0]] = [];

                    if (is_array($NewLocales))
                    foreach ($NewLocales as $NewLocale)
                        $Locales[$Slices[0]] = F::Merge($Locales[$Slices[0]], $NewLocale);
                }

                $szSlices = sizeof($Slices);

                $TrueMatch = false;

                for ($ic = $szSlices; $ic > 0; --$ic) // TODO Абстрагировать
                    if ($Replace = F::Dot($Locales, $cMatch = implode('.', array_slice($Slices, 0, $ic))))
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
