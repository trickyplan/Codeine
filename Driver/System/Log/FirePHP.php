<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: FirePHP Logger
     * @package Codeine
     * @subpackage Drivers
     * @version 0.2
     * @date 11.11.10
     * @time 4:03
     */

    $Initialize = function ($Call)
    {
        include Server::Locate('Package','FirePHPCore/FirePHP.class.php');

        return FirePHP::getInstance(true);
    };

    $Log = function ($Call)
    {
        return $Call['Writer']->info($Call['Message']);
    };