<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart MD5
     * @package Codeine
     * @version 7.x
     * @date 22.11.10
     * @time 4:40
     */

    self::setFn('Get', function ($Call)
    {
        return crypt($Call['Value'], $Call['Salt']);
    });
