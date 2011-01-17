<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart random
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 23:55
     */

    self::Fn('Generate', function ($Call)
    {
        return rand($Call['Min'],$Call['Max']);
    });