<?php

    /* Codeine Framework
     * @author BreathLess
     * @description: JSON Routing
     * @package Codeine
     * @version 6.0
     * @date 10.11.10
     * @time 23:16
     */

    self::Fn('Route', function ($Call)
    {
        // TODO Optional JSON Lint
        if (is_string($Call['Value']))
        {
            $Routed = json_decode($Call['Value'], true);
            if ($Routed !== false)
                return $Routed;
            else
                return null;
        }
        else
            return null;
    });
