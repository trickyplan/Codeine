<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All',
        [
            'Pattern' => $Call['Locale Pattern'],
            'Value'   => $Call['Output']
        ]);


        if ($Call['Parsed'])
        {
            $Locales = [];

            foreach ($Call['Parsed'][1] as &$Match)
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
                              'Scope'   => $Asset.'/Locale/'.$Call['Locale'],
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
                        F::Log('Locale '.$Locale.' not loaded', LOG_BAD);
                }

                if (($Replace = F::Dot($Locales[$Locale], $Token)) !== null)
                {
                    if (is_scalar($Replace))
                        $Match = $Replace;
                }
                else
                {
                    F::Log('Locale '.$Match, LOG_ERR);

                    if (F::Environment() === 'Development')
                        $Match = '<span class="nl">' . $Match . '</span>';
                    else
                        $Match = '';
                }
            }

            $Call['Output'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Output']);
        }

        return $Call;
    });
