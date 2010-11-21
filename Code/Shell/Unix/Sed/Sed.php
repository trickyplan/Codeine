<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description:  Piped sed command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:40
     */

    $Exec = function ($Call)
    {
        exec ($Call['Input'].' | sed -e "'.$Call['Pattern'].'"', $Output);
        return implode("\n", $Output);
    };