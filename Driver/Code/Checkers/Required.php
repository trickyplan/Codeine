<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Required Contract Checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:31
     */


    self::Fn('Check', function ($Call)
    {
        if (isset($Call['Contract']['Required']) && $Call['Contract']['Required'] && empty($Call['Data']))
            return false;
        else
            return true;
    });