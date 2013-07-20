<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeBenchmark', $Call);

            $Call['Overall'] = 0;

            foreach($Call['Benchmark']['Modules'] as $Benchmark)
            {
                $Rate = F::Run('Server.Benchmark.'.$Benchmark, 'Test', $Call);
                $Results[] = [$Benchmark, $Rate];
                $Call['Overall'] += $Rate;
            }

            $Call['Output']['Overall'][] = $Call['Overall'];

            $Call['Output']['Results'][] =
                [
                    'Type' => 'Table',
                    'Value' => $Results
                ];

        $Call = F::Hook('afterBenchmark', $Call);

        return $Call;
    });

    setFn('Send', function ($Call)
    {
        $Result = json_decode(F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Where' => 'http://codeine-framework.ru/benchmarks',
                'Data' =>
                [
                    'Host' => $Call['Host'],
                    'Score' => $Call['Overall'],
                    'Environment' => F::Environment(),
                    'Version' => $Call['Benchmark']['Version']
                ]
            ]), true);

        $Call['Output']['Top'][] = $Result['Your'];
        $Call['Output']['Total'][] = $Result['Total'];

        return $Call;
    });