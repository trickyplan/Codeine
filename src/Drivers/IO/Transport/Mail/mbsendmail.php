<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Mail Driver
     * @package Codeine
     * @version 8.x
     * @date 29.07.21
     * @time 22:20
     */

    setFn ('Open', function ($Call)
    {
        return true;
    });

    setFn('Write', function ($Call)
    {
        $HeaderStr = '';

        foreach($Call['HTTP']['Headers'] as $Key => $Value)
            $HeaderStr.= $Key.' '.$Value."\n";

        $HeaderStr.= 'Content-Type: '.$Call['HTTP']['Headers']['Content-Type:']."\n";


        return mb_send_mail($Call['Scope'], $Call['ID'], $Call['Data'], $HeaderStr);
    });