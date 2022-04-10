<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call) {
        if (is_numeric($Call['Value'])) {
            $Value = strftime($Call['Format'], $Call['Value']);
        } else {
            if (F::Environment() == 'Production') {
                $Value = '';
            } else {
                $Value = 'INCORRECT-TIMESTAMP';
            }
            F::Log('Incorrect timestamp: ' . j($Call['Value']), LOG_WARNING);
        }

        return $Value;
    });
