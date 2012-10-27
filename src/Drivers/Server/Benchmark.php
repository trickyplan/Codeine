<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Overall = 0;
        foreach($Call['Benchmarks'] as $Benchmark)
        {
            $Rate = F::Run('Server.Benchmark.'.$Benchmark, 'Test', $Call);
            $Results[] = [$Benchmark, $Rate];
            $Overall += $Rate;
        }

        $Results[] = ['Overall', $Overall];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Results
            ];

        return $Call;
    });