<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: XML RPC
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.01.11
     * @time 1:20
     */

    self::Fn('Encode', function ($Call)
    {
        return xmlrpc_encode($Call['Input']);
    });

    self::Fn('Decode', function ($Call)
    {
        return xmlrpc_decode($Call['Input']);
    });