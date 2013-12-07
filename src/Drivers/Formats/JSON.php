<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Decode', function ($Call)
    {
        $Result = json_decode($Call['Value'], true);
        if (json_last_error() > 0)
        {
            F::Log('JSON: '.json_last_error_msg(), LOG_ERR);
            F::Log($Call['Value'], LOG_ERR);
        }

        return $Result;
    });

    setFn('Encode', function ($Call)
    {
        return json_encode($Call['Value'],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    });

    setFn('Encode.Call', function ($Call)
    {
        $Call['Value'] = json_encode($Call['Value']);
        return $Call;
    });
