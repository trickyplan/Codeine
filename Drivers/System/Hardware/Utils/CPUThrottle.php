<?php

    /* Codeine
     * @author BreathLess
     * @description: CPU Throttler
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 6:54
     */

    self::Fn('Hook', function ($Call)
    {
        $FH = fopen('/proc/loadavg', 'r');
        $Data = fread($FH, 6);
        fclose($FH);
        $LA = explode(' ', $Data);
        $CPU =  trim($LA[0]);
        sleep(round($CPU));
        return $CPU;
    });
