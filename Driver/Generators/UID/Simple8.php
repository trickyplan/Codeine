<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Simple 8 digit
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 15:12
     */

    self::Fn('Get', function ($Call)
    {
        $Output = mt_rand(1,8);

        for ($ic = 0; $ic < 7; $ic++)
            $Output.= mt_rand(0,8);

        return $Output;
    });