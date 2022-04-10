<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call) {
        $Output = [];

        if (isset($Call['Value']) && is_numeric($Call['Value'])) {
            foreach ($Call['Period']['Units'] as $Period => $Value) {
                if ($Call['Value'] >= $Value) {
                    $Units = floor($Call['Value'] / $Value);
                    $Output[] = $Units . ' <codeine-locale>Formats.Period:' . $Period . '.' . ($Units % 20) . '</codeine-locale>';
                    $Call['Value'] -= $Units * $Value;
                }
            }

            if (empty($Output)) {
                $Result = '0 <codeine-locale>Formats.Period:Seconds.10</codeine-locale>';
            } else {
                $Result = implode(' ', array_slice($Output, 0, $Call['Period']['Format']));
            }
        } else {
            $Result = null;
        }

        return $Result;
    });
