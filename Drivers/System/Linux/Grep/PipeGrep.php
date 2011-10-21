<?php

    /* Codeine
     * @author BreathLess
     * @description: Piped grep command wrapper
     * @package Codeine
     * @version 
     * @date 21.11.10
     * @time 2:18
     */

    self::Fn('Exec', function ($Call)
    {
        exec ($Call['Value'].' | grep', $Output);
        return implode("\n", $Output);
    });
