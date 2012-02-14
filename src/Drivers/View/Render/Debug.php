<?php

    /* Codeine
     * @author BreathLess
     * @description: Dumper
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Detect', function ($Call)
    {
        return true;
    });

    self::setFn('Process', function ($Call)
    {
        return var_export($Call['Value'], true);
    });
