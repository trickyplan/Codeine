<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Open', function ($Call)
    {
        return openlog($Call['Scope'], LOG_ODELAY, LOG_USER);
    });

    self::setFn('Write', function ($Call)
    {
        return syslog($Call['Data'][2], $Call['Data'][0].': '.$Call['Data'][1]);
    });

    self::setFn('Close', function ($Call)
    {
        return closelog();
    });