<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart MD5
     * @package Codeine
     * @version 6.0
     * @date 22.11.10
     * @time 4:40
     */

    self::Fn('Get', function ($Call)
    {
        return md5($Call['Value']);
    });
