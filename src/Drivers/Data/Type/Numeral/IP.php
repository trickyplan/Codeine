<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        return ip2long($Call['Value']);
    });

    self::setFn('Read', function ($Call)
    {
        return long2ip($Call['Value']);
    });

    self::setFn('Populate', function ($Call)
    {
        return rand();
    });