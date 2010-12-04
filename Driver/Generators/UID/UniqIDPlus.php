<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Uniqid Prefix
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 14:53
     */

    self::Fn('Get', function ($Call)
    {
        return uniqid($Call['Prefix'],true);
    });