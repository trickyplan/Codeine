<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Open', function ($Call)
    {
        return openlog($Call['Scope'], LOG_ODELAY, LOG_USER);
    });

    self::setFn('Write', function ($Call)
    {
        return syslog($Call['Type'], $Call['Data']);
    });

    self::setFn('Close', function ($Call)
    {
        return closelog();
    });