<?php

    /* Codeine
     * @author BreathLess
     * @description: ls -la command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:20
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru ('ls -la');
    });
