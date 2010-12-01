<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart SHA1
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 4:41
     */

    self::Fn('Get', function ($Call)
    {
        return sha1($Call['Input']);
    });