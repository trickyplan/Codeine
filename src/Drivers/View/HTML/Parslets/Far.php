<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call) {
        $Queries = [];

        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices)) {
                list(, $Entity, $ID, $Field) = $Slices;
                $Queries[$Entity]['IDs'][$ID] = $ID;
                $Queries[$Entity]['Fields'][$Field] = $Field;
            }
        }
        // FIXME Return fields later

        if (empty($Queries)) {
        } else {
            $Elements = [];

            foreach ($Queries as $Entity => $KV) {
                $KV['Fields']['ID'] = 'ID';

                sort($KV['IDs']);
                $KV['IDs'] = array_unique($KV['IDs']);

                $Loaded = F::Run(
                    'Entity',
                    'Read',
                    [
                        'Entity' => $Entity,
                        'Where' => ['ID' => ['$in' => $KV['IDs']]]
                    ]
                );

                if (empty($Loaded)) {
                } else {
                    foreach ($Loaded as $Element) {
                        $Elements = F::Dot($Elements, $Entity . '.' . $Element['ID'], $Element);
                    }
                }
            }
        }

        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            if (preg_match('@^(.+)\:(.*)\:(.+)$@SsUu', $Match, $Slices)) {
                unset($Slices[0]);
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = F::Dot($Elements, implode('.', $Slices));
            } else {
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
            }
        }

        return $Call;
    });
