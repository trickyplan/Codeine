<?php

    /* Codeine
     * @author BreathLess
     * @description: Unix date wrapper
     * @package Codeine
     * @version 6.0
     * @date 09.03.11
     * @time 16:47
     */

    self::Fn('Get', function ($Call)
    {
        return passthru('date +%s');
    });
