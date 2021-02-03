<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
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

                if (empty($Element))
                    $Replaces[$Call['Parsed']['Match'][$IX]] = '';
                else
                    $Replaces[$Call['Parsed']['Match'][$IX]] = $Element[0];
            }
            else
                $Replaces[$Call['Parsed']['Match'][$IX]] = '';
        }

        return $Replaces;
     });