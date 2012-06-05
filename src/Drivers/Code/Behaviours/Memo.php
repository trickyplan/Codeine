<?php

    /* Codeine
     * @author BreathLess
     * @description Memoize call
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Run', function ($Call)
    {
        $Hash = F::hashCall($Call);

        if (!xcache_isset($Hash))
        {
            $Result = F::Run($Call['Service'], $Call['Method'], $Call['Call']);
            xcache_set($Hash, $Result);
        }
        else
        {
            $Result = xcache_get($Hash);
            F::Log($Call['Service'].':'.$Call['Method'].' memoized');
        }

        return $Result;
    });