<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Date command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 1:56
     */

    $Exec = function ($Call)
    {
        exec('date', $Output);
        return implode("\n",$Output);
    };