<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Classic Front Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 14:39
     */

    self::Fn('Run', function ($Call)
    {
        return Code::Run(
                array(
                    array('F' => 'System/Interface::Input'),// Return Autorunned Call
                    array('F' => 'View/Render::Do'),
                    array('F' => 'System/Output::Output','D' => 'HTTP')
                    ), Code::Ring2, 'Chain');
    });