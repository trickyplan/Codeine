<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return ip2long($Call['Value']);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        if (is_int($Call['Value']))
            return long2ip($Call['Value']);
        else
            return null;
    });

    setFn('Populate', function ($Call)
    {
        return long2ip(rand());
    });