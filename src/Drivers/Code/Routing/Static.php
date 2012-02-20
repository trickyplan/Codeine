<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.2
     * @date 31.08.11
     * @time 6:17
     */

    self::setFn('Route', function ($Call)
    {
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        if (is_string($Call['Run']) && isset($Call['Links'][$Call['Run']]))
        {
            if (isset($Rule['Debug']) && $Rule['Debug'] === true)
                d(__FILE__, __LINE__, $Rule);

            return $Call['Links'][$Call['Run']];
        }

        return null;
    });
