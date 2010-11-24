<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Syslog Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 17:34
    */

    self::Fn('Connect', function ($Call)
    {
        return openlog($Call['Point']['DSN'], LOG_PID | LOG_PERROR, LOG_LOCAL0);
    });

    self::Fn('Send', function ($Call)
    {
        switch ($Call['Call']['Type'])
        {
            default: $Type = LOG_INFO; break;
        }
        return syslog($Type, $Call['Call']['Message']);
    });

    self::Fn('Disconnect', function ($Call)
    {
        return closelog();
    });