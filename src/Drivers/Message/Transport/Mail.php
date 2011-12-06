<?php

    /* Codeine
     * @author BreathLess
     * @description: Mail Driver
     * @package Codeine
     * @version 6.0
     * @date 29.07.11
     * @time 22:20
     */

    self::setFn('Send', function ($Call)
    {
        // FIXME Normal Call
        return mb_send_mail($Call['To'], $Call['Subject'], $Call['Message']);
    });
