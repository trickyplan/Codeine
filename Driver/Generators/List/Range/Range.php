<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Range Generator
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 23:52
     */

    self::Fn('Generate', function ($Call)
    {
        return range($Call['From'],$Call['To']);
    });