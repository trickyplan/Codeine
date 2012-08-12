<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->connect ($Call['Server'], $Call['Port']);
        $Redis->setOption (Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // FIXME

        return $Redis;
    });

    self::setFn('Read', function ($Call)
    {
        if (($Result = $Call['Link']->lPop($Call['Scope'])) !== false)
            return array($Result);
        else
            return null;
    });

    self::setFn('Write', function ($Call)
    {
        return $Call['Link']->rPush($Call['Scope'], $Call['Data']);
    });