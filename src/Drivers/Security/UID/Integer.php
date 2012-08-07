<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.6.2
     * @date 04.12.10
     * @time 14:56
     */

    self::setFn('Get', function ($Call)
    {
        return rand(0, 2147483647);
    });
