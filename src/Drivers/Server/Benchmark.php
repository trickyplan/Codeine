<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Overall = 0;
        foreach($Call['Benchmarks'] as $Benchmark)
        {
            $Rate = F::Run('Server.Benchmark.'.$Benchmark, 'Test', $Call);
            $Results[] = [$Benchmark, $Rate];
            $Overall += $Rate;
        }

        $Call['Output']['Content'][] =
        [
            'Type' => 'Block',
            'Class' => 'hero-unit',
            'Value' => '<h2>'.$Overall.' баллов</h2>'
        ];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Results
            ];

        return $Call;
    });