<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Key) {
            if (str_contains($Key, ',') !== false) {
                $Key = explode(',', $Key);
            } else {
                $Key = [$Key];
            }

            $Value = '';

            foreach ($Key as $CMatch) {
                if (str_contains($CMatch, ':')) {
                    list ($Options, $Key) = explode(':', $CMatch);
                    $Options = F::loadOptions($Options);
                    $Value = F::Dot($Options, $Key);
                }

                if ($Value === null || $Value === '') {
                    if (isset($Call['Parsed']['Options'][$IX]['null'])) {
                        $Value = $Call['Parsed']['Options'][$IX]['null'];
                    } else {
                        $Value = 'null';
                    }
                } else {
                    if ((array)$Value === $Value) {
                        if (isset($Call['Parsed']['Options'][$IX]['json'])) {
                            $Value = j($Value);
                        } else {
                            $Value = array_shift($Value);
                        }
                    }

                    if ($Value === 0) {
                        $Value = '0';
                    }

                    if ($Value === false) {
                        $Value = 'false';
                    }

                    if ($Value === true) {
                        $Value = 'true';
                    }

                    break;
                }
            }

            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Value;
        }

        return $Call;
    });
