<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Example Neuron
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:50
     */

    self::Fn('Blur', function ($Call)
    {
        return ($Call['1st']+$Call['2nd'])/2;
    });

    self::Fn('Wind', function ($Call)
    {
        return rand(0, 10);
    });