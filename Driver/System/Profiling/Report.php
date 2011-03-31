<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 28.03.11
     * @time 2:50
     */

    self::Fn('Print', function ($Call)
    {
        echo str_replace(
            array('$Time','$Memory', '$SQL'),
            array(round(Data::Pull('Timer.Front')*1000,2),Data::Pull('Memory.Front')/1024,Data::Pull('SQL.Overall')),
            Data::Read('Layout::Profiler')
        );
    });
