<?php

    /* Codeine
     * @author BreathLess
     * @description: pwgen command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:19
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('pwgen');
    });
