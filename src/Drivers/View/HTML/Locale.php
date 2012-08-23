<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('@<locale>(.*)<\/locale>@SsUu', $Call['Output'], $Pockets))
        {
            $Language = F::Run('System.Interface.Web', 'DetectUALanguage');

            $Locales = array ();

            foreach ($Pockets[1] as $IX => $Match)
            {
                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Match));

                $AddLocales = F::Run('IO', 'Read',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $ID
                    ));

                if ($AddLocales)
                    $Locales = F::Merge($Locales, $AddLocales);

                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
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

                    for (
                        $ic = $szSlices; $ic > 0; --$ic
                    )
                        if (isset($Locales[$cMatch = implode('.', array_slice($Slices, 0, $ic))]))
                        {
                            $TrueMatch = $cMatch;
                            break;
                        }

                    if ($TrueMatch)
                        $Call['Output'] = str_replace($Pockets[0][$IX], $Locales[$TrueMatch], $Call['Output']);
                    else
                        $Call['Output'] = str_replace($Pockets[0][$IX], '<span class="nl">' . $Match . '</span>', $Call['Output']);
                }
            }
        }
        return $Call;
    });
