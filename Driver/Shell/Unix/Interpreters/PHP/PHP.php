<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: PHP CLI Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:30
     */

    $Exec = function ($Call)
    {
        exec ('php -r "'.$Call['Input'].'"', $Output);
        return implode("\n", $Output);
    };
