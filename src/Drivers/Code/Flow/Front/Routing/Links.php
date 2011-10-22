<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 6:17
     */

    self::Fn('Route', function ($Call)
    {
        // TODO Error: Not found Links Table
        if (is_string($Call['Value']) && isset($Call['Links'][$Call['Value']]))
            return $Call['Links'][$Call['Value']];
    });
