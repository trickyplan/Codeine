<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart Serializer for Caching
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 4:36
     */

    self::Fn('Do', function ($Call)
    {

        return sha1(serialize($Call['Input']));
    });