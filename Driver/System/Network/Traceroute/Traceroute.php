<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Wrapper
     * @description: Tracert
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 0:49
     */

    self::Fn('Full', function ($Call)
    {
        exec('traceroute '.$Call['Host'], $exec);
        array_shift($exec);
        return $exec;
    });

    self::Fn('Hops', function ($Call)
    {
        exec('traceroute '.$Call['Host'], $exec);
        return count($exec)-1;
    });
