<?php

    /* Codeine
     * @author BreathLess
     * @description: Null Transport
     * @package Codeine
     * @version 7.6.2
     * @date 29.07.21
     * @time 21:45
     */

    self::setFn('Send', function ($Call)
    {
        return null;
    });

    self::setFn('Receive', function ($Call)
    {
        return null;
    });
