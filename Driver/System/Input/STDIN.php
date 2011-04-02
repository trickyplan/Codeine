<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: StdIn Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 17.11.10
     * @time 20:22
     */

    self::Fn('Detect', function ($Call)
    {
        return false; sizeof(file_get_contents('php://input'))>0;
    });

    self::Fn('Input', function ($Call)
    {
        return var_dump(file_get_contents('php://stdin'));
    });
