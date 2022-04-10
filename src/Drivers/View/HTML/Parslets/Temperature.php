<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet
     * @package Codeine
     * @version 6.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $From = F::Dot($Call['Parsed'], 'Options.' . $IX . '.from') ? F::Dot(
                $Call['Parsed'],
                'Options.' . $IX . '.from'
            ) : 'Kelvin';
            $To = F::Dot($Call['Parsed'], 'Options.' . $IX . '.to') ? F::Dot(
                $Call['Parsed'],
                'Options.' . $IX . '.to'
            ) : 'Celsius';

            $Match = trim($Match);

            if (is_scalar($Match) && isset($From) && isset($To) && $Match !== 'null') {
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] =
                    round(
                        F::Run(
                            'Science.Math.Conversion.Temperature',
                            'Do',
                            [
                                'From' => $From,
                                'To' => $To,
                                'Value' => $Match
                            ]
                        )
                    ) . $Call['View']['HTML']['Parslets']['Temperature']['Unit'][$To];
            } else {
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
            }
        }

        return $Call;
    });
