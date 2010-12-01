<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: ls command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:20
     */

    $Exec = function ($Call)
    {
        return passthru('ls');
    };