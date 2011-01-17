<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Russian Roulette Emulator
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 5:19
     */

    self::Fn('Check', function ($Call)
    {
        $RR = mt_rand(0,6);
    	return $RR == 6 ? true:false;
    });