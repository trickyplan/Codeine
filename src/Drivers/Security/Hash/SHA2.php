<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart SHA1
     * @package Codeine
     * @version 7.6.2
     * @date 22.11.10
     * @time 4:41
     */

    self::setFn('Get', function ($Call)
    {
        return hash('sha512', $Call['Value']);
    });

