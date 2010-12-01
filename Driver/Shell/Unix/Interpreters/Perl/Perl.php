<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Perl Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:27
     */

    $Exec = function ($Call)
    {
        return passthru('perl -e \''.$Call['Input'].'\'');
    };