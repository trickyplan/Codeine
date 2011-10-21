<?php

    /* Codeine
     * @author BreathLess
     * @description: Russian Roulette Emulator
     * @package Codeine
     * @version 6.0
     * @date 24.11.10
     * @time 5:19
     */

    self::Fn('Check', function ($Call)
    {
        // TODO Кодеинизировать
        $RR = mt_rand(0,6);
    	return $RR == 6 ? true:false;
    });
