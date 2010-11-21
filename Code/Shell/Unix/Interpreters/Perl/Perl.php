<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Perl Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 2:27
     */

    $Exec = function ($Call)
    {
        exec ('perl -e \''.$Call['Input'].'\'', $Output);
        return implode("\n", $Output);
    };