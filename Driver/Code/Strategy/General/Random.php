<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Random Strategy
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.11.10
     * @time 1:52
     */

    $Select = function ($Call)
    {
        return (rand(0,100)%2 == 0);
    };