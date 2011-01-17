<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Classic Front Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 14:39
     */

    self::Fn('Run', function ($Call)
    {
        return Code::Run(
            array(
                array('N' => 'System.Input','F' => 'Input'),// Return Autorunned Call
                array('N' => 'View.Render', 'F' => 'Do'),
                array('N' => 'System.Output.HTTP', 'F' => 'Output')
                ), Code::Ring2, 'Chain');
    });