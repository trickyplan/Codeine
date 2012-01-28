<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.0
     * @date 13.08.11
     * @time 22:46
     */

    self::setFn('Decode', function ($Call)
    {
        return json_decode($Call['Value'], true);
    });

    self::setFn('Encode', function ($Call)
    {
        return json_encode($Call['Value']);
    });

    self::setFn('Encode.Call', function ($Call)
    {
        $Call['Value'] = json_encode($Call['Value']);
        return $Call;
    });
