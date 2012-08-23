<?php

    /* Codeine
     * @author BreathLess
     * @description Memoize call
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Run', function ($Call)
    {
        $Hash = '';

        if (isset($Call['Contract']))
            {
                foreach($Call['Contract']['Call'] as $Arg => $Node)
                    $Hash.= $Call[$Arg];

                if (!xcache_isset($Hash))
                {
                    $Result = F::Run($Call['Service'], $Call['Method'], $Call['Call']);
                    xcache_set($Hash, $Result);
                }
                else
                {
                    $Result = xcache_get($Hash);
                }
        }
        else
            $Result = F::Run($Call['Service'], $Call['Method'], $Call['Call']);;

        return $Result;
    });