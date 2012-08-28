<?php

    /* Codeine
     * @author BreathLess
     * @description: JSON Router
     * @package Codeine
     * @version 7.x
     * @date 31.08.11
     * @time 6:17
     */

    self::setFn('Route', function ($Call)
    {
        if (is_string($Call['Run']))
            $Call['Run'] = json_decode($Call['Run'], true);

        return $Call;
    });
