<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Test Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:38
     */

    self::Fn('Test', function ($Call)
    {
        Code::Run(
            array(
                 'N' => 'Security.Hash',
                 'F' => 'Get',
                 'Input' => ''
            )
        , Code::Ring2, null, 'Mockup');
    });