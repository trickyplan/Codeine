<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 2:43
     */

    self::Fn('Start', function ($Call)
    {
        return Data::Push('Memory.'.$Call['ID'], memory_get_usage(true));
    });

    self::Fn('Stop', function ($Call)
    {
        return Data::Push('Memory.'.$Call['ID'], memory_get_usage(true)-Data::Pull('Memory.'.$Call['ID']));
    });
