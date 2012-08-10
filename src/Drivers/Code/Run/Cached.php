<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Run', function ($Call)
    {
        $ID = F::hashCall($Call['Run']);

        $Result = F::Run('IO', 'Read', array
                             (
                                'Storage' => 'Run Cache',
                                'Where' => $ID
                             ));

        if (null === $Result)
        {
            $Result = F::Run($Call['Run']['Service'], $Call['Run']['Method'], $Call['Run']['Call']);

            F::Run('IO', 'Write', array
               (
                   'Storage' => 'Run Cache',
                   'Where'   => $ID,
                   'TTL'     => 15,
                   'Data'    => $Result
               ));
        }

        return $Result;
     });