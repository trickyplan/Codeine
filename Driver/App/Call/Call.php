<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Call controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 02.12.10
     * @time 0:26
     */

    self::Fn('Call', function ($Call)
    {
        return Code::Run(file_get_contents("php://input"));
    });
