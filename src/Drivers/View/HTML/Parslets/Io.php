<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices))
            {
                list(,$Storage, $Scope, $Where) = $Slices;

                    $Element = F::Run('IO', 'Read',
                        [
                            'Storage' => $Storage,
                            'Scope' => $Scope,
                            'Where'  => $Where
                        ]);

                    if (!empty($Element))
                        $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Element[0],$Call['Output']);
                    else
                        $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '', $Call['Output']);
            }
            else
                $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '' . $Match, $Call['Output']);

        }

        return $Call;
     });