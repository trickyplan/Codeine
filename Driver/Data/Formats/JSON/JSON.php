<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Default JSON Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 14.11.10
     * @time 16:05
     */

    self::Fn('Decode', function ($Call)
    {
        return json_decode($Call['Input'], true);
    });

    self::Fn('Encode', function ($Call)
    {
        return json_encode($Call['Inpu']);
    });

