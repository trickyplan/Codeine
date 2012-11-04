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

    setFn('Read', function ($Call)
    {
        return long2ip($Call['Value']);
    });

    setFn('Populate', function ($Call)
    {
        return rand();
    });