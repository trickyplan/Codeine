<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Chain Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 0:30
     */

    self::Fn('Run', function ($Call)
    {
        $Result = Code::Run($Call['Call'][0]);

        unset($Call['Call'][0]);

        foreach ($Call['Call'] as $IX => $OneCall)
        {
            $OneCall['Input'] = $Result;

            if (isset($Call['Call'][$IX+1]))
                $OneCall['ChainNext'] = $Call['Call'][$IX+1];

            if (isset($Call['Call'][$IX-1]))
                $OneCall['ChainPrev'] = $Call['Call'][$IX-1];

            $Result = Code::Run($OneCall, $Call['Mode']);
        }

        return $Result;
    });