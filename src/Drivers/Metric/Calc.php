<?php

    setFn('Average', function ($Call)
    {
        $Call = F::Hook('beforeMetricCalcAverage', $Call);

        $Rows = F::Run('Metric.Get', 'Row', $Call);

        $Call['Result'] = F::Run(
            'Science.Math.Statistics.Mean.Arithmetic',
            'Calculate',
            $Call,
            [
                'Values' => array_column($Rows, 'Value')
            ]
        );

        $Call = F::Hook('afterMetricCalcAverage', $Call);

        return $Call['Result'];
    });

    setFn('Count', function ($Call)
    {
        $Call = F::Hook('beforeMetricCalcCount', $Call);

        $Call['Result'] = F::Run('IO', 'Execute', $Call,
            [
                'Execute'   => 'Count',
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where'     => $Call['Where']
            ]);

        $Call = F::Hook('afterMetricCalcCount', $Call);

        return $Call['Result'];
    });

    setFn('Max', function ($Call)
    {
        $Call = F::Hook('beforeMetricCalcMax', $Call);

        $Rows = F::Run('Metric.Get', 'Row', $Call);
        $Call['Result'] = max(array_column($Rows, 'Value'));

        $Call = F::Hook('afterMetricCalcMax', $Call);

        return $Call['Result'];
    });

    setFn('Min', function ($Call)
    {
        $Call = F::Hook('beforeMetricCalcMin', $Call);

        $Rows = F::Run('Metric.Get', 'Row', $Call);
        $Call['Result'] = min(array_column($Rows, 'Value'));

        $Call = F::Hook('afterMetricCalcMin', $Call);

        return $Call['Result'];
    });

    setFn('Sum', function ($Call)
    {
        $Call = F::Hook('beforeMetricCalcSum', $Call);

        $Rows = F::Run('Metric.Get', 'Row', $Call);

        $Values = array_column($Rows, 'Value');

        $Call['Result'] = array_sum($Values);

        $Call = F::Hook('afterMetricCalcSum', $Call);

        return $Call['Result'];
    });