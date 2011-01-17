<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Twisted rand
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 23:58
     */

    self::Fn('Generate', function ($Call)
    {
        return mt_rand($Call['Min'],$Call['Max']);
    });