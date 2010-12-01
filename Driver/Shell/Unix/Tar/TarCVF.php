<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: tar cvf command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:24
     */

    $Exec = function ($Call)
    {
        return passthru('tar cvf '.$Call['Input']);
    };