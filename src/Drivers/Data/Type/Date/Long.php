<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call) {
        if (isset($Call['Value']) && !empty($Call['Value']) && $Call['Value'] > 0) {
            if (is_numeric($Call['Value'])) {
                return (int)$Call['Value'];
            } else {
                $DT = new DateTime($Call['Value']);
                return $DT->getTimestamp();
            }
        } else {
            return null;
        }
    });

    setFn(['Read', 'Where'], function ($Call) {
        return (int)$Call['Value']; // Separate Where?
    });

    setFn('Populate', function ($Call) {
        return rand(0, time());
    });
