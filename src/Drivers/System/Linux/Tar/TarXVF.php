<?php

    /* Codeine
     * @author BreathLess
     * @description: tar xvf command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:24
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('tar xvf '.$Call['Value']);
    });
