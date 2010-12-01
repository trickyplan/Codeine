<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: pwgen command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:19
     */

    $Exec = function ($Call)
    {
        return passthru('pwgen');
    };