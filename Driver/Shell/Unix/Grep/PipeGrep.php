<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Piped grep command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 
     * @date 21.11.10
     * @time 2:18
     */

    self::Fn('Exec', function ($Call)
    {
        exec ($Call['Input'].' | grep', $Output);
        return implode("\n", $Output);
    });