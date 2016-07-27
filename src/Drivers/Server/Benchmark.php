<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Server',
            'ID' => 'Benchmark'
        ];
        $Call = F::Hook('beforeBenchmark', $Call);

            $Call['Overall'] = 0;

            foreach($Call['Benchmark']['Modules'] as $BenchmarkName => $BenchmarkCall)
            {
                $Rate = round(F::Run('Server.Benchmark.'.$BenchmarkName, 'Test', $BenchmarkCall)*$BenchmarkCall['Weight'], 2);
                $Results[] = [$BenchmarkName, $Rate];
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
        $Result = F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Output Format'  => 'Formats.JSON',
                'Where' =>
                [
                    'ID' => 'https://codeine-framework.org/benchmarks'
                ],
                'Data' =>
                [
                    'Host' => $Call['HTTP']['Host'],
                    'Score' => $Call['Overall'],
                    'Environment' => F::Environment(),
                    'Version' => $Call['Benchmark']['Version']
                ]
            ])[0];

        $Call['Output']['Top'][] = $Result['Your'];
        $Call['Output']['Total'][] = $Result['Total'];

        return $Call;
    });