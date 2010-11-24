<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: IRB Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:27
     */

    $Exec = function ($Call)
    {
        exec ('ruby -e "'.$Call['Input'].'"', $Output);
        return implode("\n", $Output);
    };