<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
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
                foreach ($Call['Regex'] as $Name => $Call['Routing']['Rule'])
                {
                    // «Оживляем» переменную
                    // $Call['Routing']['Rule']['Match'] = F::Live($Call['Routing']['Rule']['Match']);

                    $ix++;

                    $Call['Routing']['Rule']['Match'] = $Call['Regex Pattern']['Prefix'].$Call['Routing']['Rule']['Match'].$Call['Regex Pattern']['Postfix'];

                    if (!isset($Call['Routing']['Rule']['Weight']))
                        $Call['Routing']['Rule']['Weight'] = 0;

                    if ($Call['Routing']['Rule']['Weight'] > $Weight)
                    {
                        F::Log($Call['Routing']['Rule']['Match'], LOG_DEBUG);
                        $Matches = [];
                        
                        if (preg_match($Call['Routing']['Rule']['Match'], $Call['Run'], $Matches))
                        {
                            $Call['Routing']['Rule'] = F::Map($Call['Routing']['Rule'], function (&$Key, &$Value, $Data, $FullKey, &$Array) use ($Matches)
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

                            F::Log('Rule *'.$Name.'* matched', LOG_DEBUG);

                            if (isset($Call['Routing']['Rule']['Mixin']) && $Call['Routing']['Rule']['Mixin'])
                            {
                                $Call['Mixin'] = $Call['Routing']['Rule'];
                                $Call['Run'] = str_replace($Matches[0], '', $Call['Run']);
                                F::Log('Regex *mixin* *'.$Name.'* applied *'.$Call['Run'].'*', LOG_INFO);
                            }
                            else
                            {
                                $Weight = $Call['Routing']['Rule']['Weight'];
                                $Decision = $Call['Routing']['Rule'];
                                $Selected = $Name;
                            }
                            /*if (isset($Call['Routing']['Rule']['Last']) && $Call['Routing']['Rule']['Last'])
                                break;*/
                        }
                    }
                }
            }
        else
            F::Log('Routes table corrupted', LOG_CRIT); // FIXME

        if (isset($Selected))
            F::Log('Rule *'.$Selected.'* selected with weight *'.$Weight.'* after '.($ix.' of '.sizeof($Call['Regex'])), LOG_INFO);
        else
            F::Log('Rule not selected', LOG_INFO);

        $Call['Run'] = $Decision;

        if (isset($Call['Mixin']))
            $Call['Run'] = F::Merge($Call['Run'], $Call['Mixin']);

        unset($Call['Regex']);

        return $Call;
    });