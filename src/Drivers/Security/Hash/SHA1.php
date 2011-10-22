<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart SHA1
     * @package Codeine
     * @version 6.0
     * @date 22.11.10
     * @time 4:41
     */

    self::Fn('Get', function ($Call)
    {
        return sha1($Call['Value']);
    });

