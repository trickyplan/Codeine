<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            if (preg_match('@^(.+)\:(.+)$@SsUu', $Call['Parsed']['Value'][$IX], $Slices)) {
                list(, $Entity, $ID) = $Slices;

                if (empty($ID)) {
                    $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
                } else {
                    $Scope = $Entity . '/' . (F::Dot($Call['Parsed'], 'Options.' . $IX . '.scope') ? F::Dot(
                            $Call['Parsed'],
                            'Options.' . $IX . '.scope'
                        ) : 'Show');
                    $Template = F::Dot($Call['Parsed'], 'Options.' . $IX . '.template') ? F::Dot(
                        $Call['Parsed'],
                        'Options.' . $IX . '.template'
                    ) : 'Tag';

                    $Element = F::Run(
                        'Entity',
                        'Read',
                        [
                            'Entity' => $Entity,
                            'Where' => $ID,
                            'One' => true
                        ]
                    );

                    if (empty($Element)) {
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
                    } else {
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = F::Run(
                            'View',
                            'Load',
                            $Call,
                            [
                                'Scope' => $Scope,
                                'ID' => $Template,
                                'Data' => $Element,
                                'Options' => F::Dot($Call['Parsed'], 'Options.' . $IX)
                            ]
                        );
                    }
                }
            } else {
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
            }
        }

        return $Call;
    });
