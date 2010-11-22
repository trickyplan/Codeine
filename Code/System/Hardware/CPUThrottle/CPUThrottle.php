<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: CPU Throttler
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 6:54
     */

    $Hook = function ($Call)
    {
        $FH = fopen('/proc/loadavg', 'r');
        $Data = fread($FH, 6);
        fclose($FH);
        $LA = explode(' ', $Data);
        $CPU =  trim($LA[0]);
        sleep(round($CPU));
        return $CPU;
    };