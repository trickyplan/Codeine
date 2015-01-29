<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return openlog($Call['Scope'].REQID, LOG_ODELAY, LOG_USER);
    });

    setFn('Write', function ($Call)
    {
        foreach ($Call['Data'] as $Row)
            syslog($Row[0], $Row[2]);

        return true;
    });

    setFn('Close', function ($Call)
    {
        return closelog();
    });