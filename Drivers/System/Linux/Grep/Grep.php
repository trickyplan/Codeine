<?php

    /* Codeine
     * @author BreathLess
     * @description: Grep command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:09
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('grep '.$Call['Value']);
    });
