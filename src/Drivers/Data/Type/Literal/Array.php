<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call) {
        if (isset($Call['Separator'])) {
        } else {
            $Call['Separator'] = ';';
        }

        if (is_array($Call['Value']) && !empty($Call['Value'])) {
            return implode($Call['Separator'], $Call['Value']);
        } else {
            return $Call['Value'];
        }
    });

    setFn('Read', function ($Call) {
        if (isset($Call['Separator'])) {
        } else {
            $Call['Separator'] = ';';
        }

        return explode($Call['Separator'], $Call['Value']);
    });

    setFn('Where', function ($Call) {
        return $Call['Value'];
    });
