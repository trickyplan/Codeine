<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        foreach($Call['Benchmarks'] as $Benchmark)
        {
            $Results[] = [$Benchmark, F::Run('Server.Benchmark.'.$Benchmark, 'Test', $Call)];
        }

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Results
            ];

        return $Call;
    });