<?php

    /* Codeine
     * @author BreathLess
     * @description: Mail Driver
     * @package Codeine
     * @version 7.x
     * @date 29.07.21
     * @time 22:20
     */

    self::setFn ('Open', function ($Call)
    {
        return true;
    });

    self::setFn('Write', function ($Call)
    {
        $HeaderStr = '';

        foreach($Call['Headers'] as $Key => $Value)
            $HeaderStr.= $Key.' '.$Value."\n";

        $HeaderStr.= 'Content-type: '.$Call['Headers']['Content-type:']."\n";

        return mb_send_mail($Call['Scope'], $Call['ID'], $Call['Data'], $HeaderStr);
    });
