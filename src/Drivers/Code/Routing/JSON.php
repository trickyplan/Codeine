<?php

    /* Codeine
     * @author BreathLess
     * @description: JSON Router
     * @package Codeine
     * @version 7.1
     * @date 31.08.11
     * @time 6:17
     */

    self::setFn('Route', function ($Call)
    {
        if (is_string($Call['Value']))
            return json_decode($Call['Value'], true);
        else
            return null;
    });
