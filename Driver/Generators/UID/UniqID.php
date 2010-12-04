<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Uniqid wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 14:14
     */

    self::Fn('Get', function ($Call)
    {
        return uniqid();
    });