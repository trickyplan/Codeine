<?php

    /* Codeine
     * @author BreathLess
     * @description: JSON Router
     * @package Codeine
     * @version 8.x
     * @date 31.08.11
     * @time 6:17
     */

    setFn('Route', function ($Call)
    {
        if (is_string($Call['Run']))
            $Call['Run'] = jd($Call['Run'], true);

        return $Call;
    });
