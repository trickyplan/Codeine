<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart MD5
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 4:40
     */

    self::Fn('Get', function ($Call)
    {
        return md5($Call['Input']);
    });