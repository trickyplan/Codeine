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

        if (($Result = F::Get($Hash)) === null)
        {
            $Result = F::Run($Call['Service'], $Call['Method'], $Call['Call']);
            F::Set($Hash, $Result);
        }

        return $Result;
    });