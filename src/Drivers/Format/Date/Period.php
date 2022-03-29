<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call) {
        if (isset($Call['Value']) && is_numeric($Call['Value'])) {
            $Time = time();

            if ($Time < $Call['Value']) {
                $Prefix = '<codeine-locale>Formats.Period:Future.Prefix</codeine-locale> ';
                $Postfix = ' <codeine-locale>Formats.Period:Future.Postfix</codeine-locale>';
            } elseif ($Time > $Call['Value']) {
                $Prefix = '<codeine-locale>Formats.Period:Past.Prefix</codeine-locale> ';
                $Postfix = ' <codeine-locale>Formats.Period:Past.Postfix</codeine-locale>';
            } else {
                return '<codeine-locale>Formats.Period:Now</codeine-locale>';
            }

            $Call['Value'] = abs($Time - $Call['Value']);

            $Output = [];

            foreach ($Call['Period']['Units'] as $Period => $Value) {
                if ($Call['Value'] >= $Value) {
                    $Units = floor($Call['Value'] / $Value);
                    $Output[] = $Units . ' <codeine-locale>Formats.Period:' . $Period . '.' . ($Units % 20) . '</codeine-locale>';
                    $Call['Value'] -= $Units * $Value;
                }
            }

            return $Prefix . implode(' ', array_slice($Output, 0, $Call['Period']['Format'])) . $Postfix;
        } else {
            return null;
        }
    });