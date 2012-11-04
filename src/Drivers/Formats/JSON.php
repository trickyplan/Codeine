<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Decode', function ($Call)
    {
        return json_decode($Call['Value'], true);
    });

    setFn('Encode', function ($Call)
    {
        return json_encode($Call['Value'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    });

    setFn('Encode.Call', function ($Call)
    {
        $Call['Value'] = json_encode($Call['Value']);
        return $Call;
    });
