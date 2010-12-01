<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: krumo wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 5:21
     */

    self::Fn('Variable', function ($Call)
    {
        krumo($Call['Input']);
    });