<?php

    /* Codeine
     * @author BreathLess
     * @description: Mail Driver
     * @package Codeine
     * @version 7.2
     * @date 29.07.21
     * @time 22:20
     */

    self::setFn ('Open', function ($Call)
    {
        return true;
    });

    self::setFn('Write', function ($Call)
    {
        return mb_send_mail($Call['Scope'], $Call['ID'], $Call['Data']);
    });
