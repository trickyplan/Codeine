<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: man command wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 2:22
     */

    $Exec = function ($Call)
    {
        if (!isset($Call['Input']))
            $Call['Input'] = 'man';
        
        return passthru('man '.$Call['Input']);
    };