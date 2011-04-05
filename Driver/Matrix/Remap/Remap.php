<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 03.04.11
     * @time 14:30
     */

    self::Fn('Keys', function ($Call)
    {
        foreach ($Call['Map'] as $From => $To)
        {
            $Call['Input'][$To] = $Call['Input'][$From];
            unset($Call['Input'][$From]);
        }
        return $Call['Input'];
    });
