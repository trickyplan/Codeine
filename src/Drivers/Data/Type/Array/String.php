<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call) {
        $Call['Value'] = (array)$Call['Value'];

        foreach ($Call['Value'] as &$Value) {
            $Value = (string)$Value;
        }

        return $Call['Value'];
    });

    setFn('Read', function ($Call) {
        $Call['Value'] = (array)$Call['Value'];

        foreach ($Call['Value'] as &$Value) {
            if (is_scalar($Value)) {
                $Value = (string)$Value;
            } else {
                F::Log('Non-scalar value *' . j($Value) . '*, skipped', LOG_NOTICE);
            }
        }

        return $Call['Value'];
    });

    setFn('Where', function ($Call) {
        return $Call['Value'];
    });