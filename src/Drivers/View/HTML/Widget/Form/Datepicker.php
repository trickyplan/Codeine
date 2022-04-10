<?php

    setFn('Format', function ($Call) {
        if (empty($Call['Value'])) {
        } else {
            $Call['Value'] = date('Y-m-d\Th:m:s', $Call['Value']);
        }

        return $Call['Value'];
    });
