<?php

    /* Codeine
     * @author BreathLess
     * @description: top -n 1 command wrapper
     * @package Codeine
     * @version 6.0
     * @date 24.11.10
     * @time 20:58
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('top -n 1');
    });
