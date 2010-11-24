<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: top -n 1 command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 20:58
     */

    $Exec = function ($Call)
    {
        exec ('top -n 1', $Output);
        return implode("\n", $Output);
    };