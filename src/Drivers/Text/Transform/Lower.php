<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Apply', function ($Call) {
        if (is_string($Call['Value'])) {
            $Call['Value'] = mb_strtolower($Call['Value']);
        }

        return $Call;
    });

    setFn('Do', function ($Call) {
        if (is_string($Call['Value'])) {
            $Call['Value'] = mb_strtolower($Call['Value']);
        }

        return $Call['Value'];
    });
