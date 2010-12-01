<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON Mapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 18:34
     */

    self::Fn('Load', function ($Call)
    {
        return json_decode($Call['Data'], true);
    });