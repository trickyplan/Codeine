<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
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
                if (strpos($Match, ',') !== false)
                    $Matches = explode(',', $Match);
                else
                    $Matches = [$Match];
                    
                foreach ($Matches as $Match)
                {
                    if (strpos($Match, ':') !== false)
                        list($Locale, $Token) = explode(':', $Match);
                    else
                    {
                        $Locale = 'Locale';
                        $Token = $Match;
                    }
    
                    if (isset($Locales[$Locale]))
                        break;
                }
                
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
                    F::Log('Locale '.$Locale.' not loaded', LOG_NOTICE);

                if (($Replace = F::Dot($Locales[$Locale], $Token)) !== null)
                {
                    if (is_scalar($Replace))
                        $Match = $Replace;
                }
                else
                {
                    F::Log('Unresolved locale *'.$Match.'*', LOG_WARNING);

                    if (F::Environment() === 'Development')
                        $Match = '<span class="nl" lang="'.$Call['Locale'].'">' . $Match . '</span>';
                    else
                        $Match = '';
                }
            }

            $Call['Output'] = str_replace($Call['Parsed'][0], $Call['Parsed'][1], $Call['Output']);
        }

        return $Call;
    });
