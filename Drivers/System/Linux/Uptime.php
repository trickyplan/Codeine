<?php

    /* Codeine
     * @author BreathLess
     * @description: uptime command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:21
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('uptime');
    });
