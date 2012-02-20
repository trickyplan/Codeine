<?php

    /* Codeine
     * @author BreathLess
     * @description: Dumper
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Detect', function ($Call)
    {
        return true;
    });

    self::setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'text/plain';
        return var_export($Call['Value'], true);
    });
