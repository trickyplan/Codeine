<?php

    /* Codeine
     * @author BreathLess
     * @description: Date command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 1:56
     */

    self::Fn('Exec', function ($Call)
    {
        return shell_exec('date');
    });
