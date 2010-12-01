<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: KDE Notify
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 20:38
     */

    self::Fn('Connect', function ($Call)
    {
        return true;
    });

    self::Fn('Send', function ($Call)
    {
        return passthru('notify-send '.$Call['Call']['Type'].' '.$Call['Call']['Message']);
    });