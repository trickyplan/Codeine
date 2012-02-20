<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.1
     * @date 13.08.11
     * @time 22:46
     */

    self::setFn('Decode', function ($Call)
    {
        $Result = json_decode($Call['Value'], true);
        return $Result['result'];
    });

    self::setFn('Encode', function ($Call)
    {
        return json_encode(array(
                               'jsonrpc' => '2.0',
                               'result' => $Call['Value']
                           ));
    });