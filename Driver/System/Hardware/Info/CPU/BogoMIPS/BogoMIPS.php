<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 0:08
     */

    self::Fn('Get', function ($Call)
    {
        exec('cat /proc/cpuinfo | grep bogomips', $exec);
        if (preg_match_all('/([\d\.]+)/', implode ('', $exec), $pockets))
            return array_sum($pockets[0]);
        else
            return null;
    });
