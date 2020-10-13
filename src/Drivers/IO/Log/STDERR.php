<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return;
    });

    setFn('Write', function ($Call)
    {
        foreach ($Call['Data'] as $Row)
            fwrite(STDERR, $Row[2]);

        return true;
    });

    setFn('Close', function ($Call)
    {
        return;
    });