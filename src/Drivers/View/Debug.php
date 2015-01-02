<?php

    /* Codeine
     * @author BreathLess
     * @description: Dumper
     * @package Codeine
     * @version 8.x
     */

    setFn('Detect', function ($Call)
    {
        return true;
    });

    setFn('Render', function ($Call)
    {
        $Call['HTTP']['Headers']['Content-type:'] = 'text/plain';
        return var_export($Call['Value'], true);
    });
