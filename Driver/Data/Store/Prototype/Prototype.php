<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Prototype Store Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 23:00
     */

    self::Fn('Version', function ($Call)
    {
        return sha1(serialize(Data::Read($Call)));
    });