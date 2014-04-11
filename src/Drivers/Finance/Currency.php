<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('GetRates', function ($Call)
    {
        return F::Run('Code.Run.Cached', 'Run',
            [
                'Run' => $Call['Currencies'][$Call['From']][$Call['To']]['Rate'],
                'RTTL' => $Call['Currency TTL']
            ]
        );
    });

    setFn('Convert', function ($Call)
    {
        return $Call['Value']*F::Run('Code.Run.Cached', 'Run',
            [
                'Run' => $Call['Currencies'][$Call['From']][$Call['To']]['Rate'],
                'RTTL' => $Call['Currency TTL']
            ]
        );
    });