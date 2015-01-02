<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if ($Call['Value'] == 0)
            return 0;
        else
            return (int) $Call['Value']/abs($Call['Value']);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return (int) $Call['Value'];
    });