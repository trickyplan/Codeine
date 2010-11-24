<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Grep command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:09
     */

    $Exec = function ($Call)
    {
        exec ('grep '.$Call['Input'], $Output);
        return implode("\n", $Output);
    };