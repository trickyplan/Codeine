<?php

    /* Codeine
     * @author BreathLess
     * @description: Dumper
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Detect', function ($Call)
    {
        return true;
    });

    self::Fn('Process', function ($Call)
    {
        return var_export($Call['Value'], true);
    });
