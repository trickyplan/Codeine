<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: SOAP Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 27.03.11
     * @time 22:16
     */

    self::Fn('Run', function ($Call)
    {
        $client = new SoapClient($Call['Call']['N']);
        return $client->$Call['Call']['F']($Call['Call']);
    });
