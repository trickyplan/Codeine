<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: WDDX Native
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 15:46
     */

    self::Fn('Encode', function ($Call)
    {
        return wddx_serialize_vars($Call['Input']);
    });

    self::Fn('Decode', function ($Call)
    {
        return wddx_unserialize($Call['Input']);
    });