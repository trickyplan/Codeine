<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     * @date 31.08.11
     * @time 6:17
     */

    setFn('Route', function ($Call)
    {
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        // TODO Error: Not found Regex Table
        $Decision = null;
        $Weight = -255;

        if (isset($Call['Regex']))
            {
                $ix = 0;
                foreach ($Call['Regex'] as $Name => $Rule)
                {
                    // «Оживляем» переменную
                    $Rule['Match'] = F::Live($Rule['Match']);

                    $ix++;

                    $Rule['Match'] = $Call['Regex Pattern']['Prefix'].$Rule['Match'].$Call['Regex Pattern']['Postfix'];

                    if (!isset($Rule['Weight']))
                        $Rule['Weight'] = 0;

                    if ($Rule['Weight'] > $Weight)
                    {
                        F::Log($Rule['Match'], LOG_DEBUG);
                        $Matches = [];

                        if (preg_match($Rule['Match'], $Call['Run'], $Matches))
                        {
                            $Rule = F::Map($Rule, function (&$Key, &$Value, $Data, $FullKey, &$Array) use ($Matches)
                            {
                                if (preg_match_all('/\$(\d+)/', $Key , $Pockets))
                                    foreach ($Pockets[1] as $IX => $Matcher)
                                        if (isset($Matches[$Matcher]))
                                        {
                                            unset($Array[$Key]);
                                            $Key = str_replace($Pockets[0][$IX], $Matches[$Matcher], $Key );
                                        }

                                if (is_string($Value) && preg_match_all('/\$(\d+)/', $Value, $Pockets))
                                    foreach ($Pockets[1] as $IX => $Matcher)
                                        if (isset($Matches[$Matcher]))
                                            $Value = str_replace($Pockets[0][$IX], $Matches[$Matcher], $Value);
                            });

                            F::Log('Regex router rule *'.$Name.'* matched', LOG_DEBUG);

                            $Weight = $Rule['Weight'];
                            $Decision = $Rule;
                            $Selected = $Name;

                            /*if (isset($Rule['Last']) && $Rule['Last'])
                                break;*/
                        }
                    }
                }
            }
        else
            F::Log('Regex routes table corrupted', LOG_CRIT); // FIXME

        if (isset($Selected))
            F::Log('Regex router rule *'.$Selected.'* selected after. '.($ix.' of '.sizeof($Call['Regex'])), LOG_INFO);
        else
            F::Log('No one regex rule selected', LOG_INFO);

        $Call['Run'] = $Decision;

        unset($Call['Regex']);

        return $Call;
    });


    setFn('Reverse', function ($Call)
    {
        return $Call;
    });