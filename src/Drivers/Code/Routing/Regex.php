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
        $Call['Mixin'] = ['Run' => []];
        
        if (isset($Call['Regex']))
        {
            $Call['Routing']['Rules Checked'] = 0;
            $Call['Routing']['URL'] = $Call['Run'];
            foreach ($Call['Regex'] as &$Rule)
                if (isset($Rule['Weight']))
                    ;
                else
                    $Rule['Weight'] = 0;

            $Call['Regex'] = F::Sort($Call['Regex'], 'Weight', SORT_DESC);
            F::Log(array_keys($Call['Regex']), LOG_DEBUG);
                
            foreach ($Call['Regex'] as $Name => $Call['Routing']['Rule'])
            {
                // «Оживляем» переменную
                // $Call['Routing']['Rule']['Match'] = F::Live($Call['Routing']['Rule']['Match']);

                $Call['Routing']['Rules Checked']++;

                $Call['Routing']['Rule']['Match'] = $Call['Regex Pattern']['Prefix'].$Call['Routing']['Rule']['Match'].$Call['Regex Pattern']['Postfix'];

                F::Log($Call['Routing']['Rule']['Match'], LOG_DEBUG);
                    $Matches = [];
                    
                if (preg_match($Call['Routing']['Rule']['Match'], $Call['Routing']['URL'], $Matches))
                {
                    F::Log('Rule *'.$Name.'* is matched', LOG_INFO);
                    
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


                    if (isset($Call['Routing']['Rule']['Mixin']) && $Call['Routing']['Rule']['Mixin'])
                    {
                        $Call['Mixin'] = F::Merge($Call['Mixin'], $Call['Routing']['Rule']);
                        F::Log('Routing URL: *'.$Call['Routing']['URL'].'*', LOG_INFO);
                        $Call['Routing']['URL'] = str_replace($Matches[0], '', $Call['Routing']['URL']);
                        F::Log('Mixin *'.$Name.'* applied.', LOG_INFO);
                        F::Log('Routing URL: *'.$Call['Routing']['URL'].'*', LOG_INFO);
                        
                        F::Log('Run: *'.$Call['Routing']['URL'].'*', LOG_INFO);
                    }
                    else
                    {
                        $Decision = $Call['Routing']['Rule'];
                        $Selected = $Name;
                        break;
                    }
                }
                else
                    F::Log('Rule *'.$Name.'* is not matched', LOG_DEBUG);
            }
        }
        else
            F::Log('Routes table corrupted', LOG_CRIT); // FIXME

        if (isset($Selected))
            F::Log('Rule *'.$Selected.'* selected with weight *'.$Weight.'* after '.($Call['Routing']['Rules Checked'].' of '.sizeof($Call['Regex'])), LOG_INFO);
        else
            F::Log('Rule not selected', LOG_INFO);

        $Call['Run'] = $Decision;

        $Call['Run'] = F::Merge($Call['Run'], $Call['Mixin']['Run']);
        
        $Call['Run']['Call']['Routing']['URL'] = $Call['Routing']['URL'];

        unset($Call['Regex']);

        return $Call;
    });