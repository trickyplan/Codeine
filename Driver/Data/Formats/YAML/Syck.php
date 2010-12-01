<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Syck Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 14.11.10
     * @time 16:08
     */

    self::Fn('Decode', function ($Call)
    {
        return syck_load($Call['Value']);
    });

    self::Fn('Encode', function ($Call)
    {
        // TODO Implement Syck Encoding
        return $Call['Value'];
    });

