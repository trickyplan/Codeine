<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->connect ($Call['Server'], $Call['Port']);
        // $Redis->setOption (Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // FIXME

        return $Redis;
    });

    setFn('Read', function ($Call)
    {
        if (($Result = $Call['Link']->lPop($Call['Scope'])) !== false)
            return [jd($Result, true)];
        else
            return null;
    });

    setFn('Write', function ($Call)
    {
        return $Call['Link']->rPush($Call['Scope'], j($Call['Data']));
    });