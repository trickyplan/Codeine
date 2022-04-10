<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet
     * @package Codeine
     * @version 6.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            $Format = F::Dot($Call['Parsed'], 'Options.' . $IX . '.format') ? F::Dot(
                $Call['Parsed'],
                'Options.' . $IX . '.format'
            ) : 'French';
            $Digits = F::Dot($Call['Parsed'], 'Options.' . $IX . '.digits') ? F::Dot(
                $Call['Parsed'],
                'Options.' . $IX . '.digits'
            ) : 0;

            $Match = trim($Match);

            if (is_scalar($Match) && isset($Format) && !empty($Match)) {
                $Match = strtr($Match, ',', '.');
                switch ($Format) {
                    case 'French':
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = F::Run(
                            'Formats.Number.French',
                            'Do',
                            ['Value' => $Match, 'Digits' => $Digits]
                        );
                        break;

                    case 'English':
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = number_format($Match, $Digits);
                        break;

                    case 'Sprintf':
                        $Sprintf = F::Dot($Call['Parsed'], 'Options.' . $IX . '.sprintf') ? F::Dot(
                            $Call['Parsed'],
                            'Options.' . $IX . '.sprintf'
                        ) : '%d';
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = F::Run(
                            'Formats.Number.Sprintf',
                            'Do',
                            ['Value' => $Match, 'Format' => $Sprintf]
                        );
                        break;

                    default:
                        $Call['Replace'][$Call['Parsed']['Match'][$IX]] = sprintf($Format, $Match);
                        break;
                }
            } else {
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Match;
            }
        }

        return $Call;
    });
