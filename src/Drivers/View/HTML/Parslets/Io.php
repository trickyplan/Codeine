<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $Ix => $Match)
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
                    $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], $Element[0],$Call['Output']);
                else
                    $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], '', $Call['Output']);
            }
            else
                $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], '' . $Match, $Call['Output']);

        }

        return $Call;
     });