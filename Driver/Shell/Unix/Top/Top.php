<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: top -n 1 command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 20:58
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('top -n 1');
    });