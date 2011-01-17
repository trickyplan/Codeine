<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: ls -la command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:20
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru ('ls -la');
    });