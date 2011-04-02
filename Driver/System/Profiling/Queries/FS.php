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
        Data::Push('FS.Overall',0);
    });

    self::Fn('Tick', function ($Call)
    {
        return Data::Push('FS.Overall',Data::Pull('FS.Overall')+1);
    });

    self::Fn('Stop', function ($Call)
    {
        return Data::Pull('FS.Overall');
    });
