<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Redis Storage
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 22:30
     */

    self::Fn('Connect', function ($Call)
    {
        if (!isset($Call['DSN']))
          $Call['DSN'] = '127.0.0.1:6379';

        list ($Server, $Port) = explode(':', $Call['DSN']);

        $Redis = new Redis();

        if ($Redis->connect($Server, $Port) === false)
            return Log::Error('Connect to Redis failed');
        else
            return $Redis;
    });

    self::Fn('Read', function ($Call)
    {
        return json_decode($Call['Store']->get(
            $Call['Point']['Scope'].
            $Call['Data']['Where']['ID']), true);
    });

    self::Fn('Create', function ($Call)
    {
        return $Call['Store']->set(
            $Call['Point']['Scope'].
            $Call['Data']['ID'],
            json_encode($Call['Data']['Data']));
    });