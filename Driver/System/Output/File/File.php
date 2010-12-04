<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: File Output
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:41
     */

    self::Fn('Output', function ($Call)
    {
        return file_put_contents($Call['Call'], $Call['Input']);
    });