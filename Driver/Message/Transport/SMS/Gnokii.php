<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Gnokii wrapper for SMS Sending
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 03.12.10
     * @time 23:45
     */

    self::Fn('Send', function ($Call)
    {
        return passthru('echo "'.$Call['Message'].'" | gnokii --sendsms '.$Call['To']);
    });