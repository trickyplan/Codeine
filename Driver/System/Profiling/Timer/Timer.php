<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 2:32
     */

    self::Fn('Start', function ($Call)
    {
        return Data::Push('Lap.'.$Call['ID'], microtime(true));
    });

    self::Fn('Stop', function ($Call)
    {
        return Data::Push('Timer.'.$Call['ID'], microtime(true)-Data::Pull('Lap.'.$Call['ID']));
    });
