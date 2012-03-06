<?php

    /* Codeine
     * @author BreathLess
     * @description XCache Data Driver
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->connect ('127.2.0.1', 6379);
        $Redis->setOption (Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);

        return $Redis;
    });

    self::setFn ('Read', function ($Call)
    {
        return $Call['Link']->get($Call['Where']['ID']);
    });

    self::setFn ('Write', function ($Call)
    {
        return (null === $Call['Data'])?
            $Call['Link']->del($Call['Where']['ID']):
            $Call['Link']->set($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });

    self::setFn ('Exist', function ($Call)
    {
        return $Call['Link']->exists ($Call['Where']['ID']);
    });