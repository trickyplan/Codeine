<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return openlog($Call['Scope'].REQID, LOG_ODELAY, LOG_USER);
    });

    setFn('Write', function ($Call)
    {
        foreach ($Call['RAW'] as $Row)
            syslog($Row[0], $Row[2]);

        return true;
    });

    setFn('Close', function ($Call)
    {
        return closelog();
    });