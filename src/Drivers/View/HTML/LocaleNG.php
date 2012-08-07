<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Process', function ($Call)
    {
        $Language = F::Run('System.Interface.Web', 'DetectUALanguage');

        $Locales = array ();

        if (isset($Call['Locales']))
            foreach ($Call['Locales'] as $Locale)
            {
                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Locale));

                $AddLocales = F::Run('IO', 'Read',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $ID
                    ))[0];

                if ($AddLocales)
                {
                    F::Log('Locale '.$Locale.' loaded');
                    $Locales = F::Merge($Locales, $AddLocales);
                }
            }

            if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
            {

                foreach (
                    $Pockets[1] as $IX => $Match
                )
                {
                    $Slices   = explode('.', trim($Match));
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
