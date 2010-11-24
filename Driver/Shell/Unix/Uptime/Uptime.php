<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: uptime command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:21
     */

    $Exec = function ($Call)
    {
        exec ('uptime', $Output);
        return implode("\n", $Output);
    };