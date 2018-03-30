<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Where', function ($Call)
    {
        if (isset($Call['Where']))
            ;
        else
            $Call['Where'] = [];
        
        if (isset($Call['Metric']['Dimensions']))
        {
            $Call['Where'] = F::Merge($Call['Where'], $Call['Metric']['Dimensions']);
            F::Log(function () use ($Call) {return 'Metric Dimensions: *'.j($Call['Where']).'*';} , LOG_INFO);
        }
        
        if (isset($Call['Metric']['Resolutions']))
        {
            $Call['Where']['Resolution'] = $Call['Metric']['Resolutions'];
            F::Log(function () use ($Call) {return 'Resolutions: *'.j($Call['Metric']['Resolutions']).'*';} , LOG_INFO);
        }
        
        $Call['Where']['Type'] = $Call['Metric']['Type'];
        F::Log(function () use ($Call) {return 'Metric Type: *'.$Call['Where']['Type'].'*';} , LOG_INFO);
        
        return $Call;
    });
    
    setFn('Row', function ($Call)
    {
        $Call = F::Hook('beforeMetricGet', $Call);
            
            $Call = F::Apply(null, 'Where', $Call);
            
            $Call['Result'] = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where'     => $Call['Where']
            ]);
        
        $Call = F::Hook('afterMetricGet', $Call);
        
        return $Call['Result'];
    });
    
    setFn('Last', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetLast', $Call);
            
            $Call = F::Apply(null, 'Where', $Call);
            
            $Call['Result'] = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where'     => $Call['Where'],
                'Sort'      =>
                [
                    'Metric.Time' => false
                ],
                'IO One'    => true
            ]);
        
        $Call = F::Hook('afterMetricGetLast', $Call);
        
        return F::Dot($Call, 'Result.Value');
    });
    
    setFn('Sum', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetCount', $Call);
        
            $Rows = F::Run(null, 'Row', $Call);
            $Values = array_column($Rows, 'Value');
           
            $Call['Result'] = array_sum($Values);
            
        $Call = F::Hook('afterMetricGetCount', $Call);
        
        return $Call['Result'];
    });
    
    setFn('Count', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetCount', $Call);
            
            $Call = F::Apply(null, 'Where', $Call);
            
            $Call['Result'] = F::Run('IO', 'Execute', $Call,
            [
                'Execute'   => 'Count',
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where'     => $Call['Where']
            ]);
        
        $Call = F::Hook('afterMetricGetCount', $Call);
        
        return $Call['Result'];
    });

    setFn('Average', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetCount', $Call);

            $Rows = F::Run(null, 'Row', $Call);

            $Call['Result'] = F::Run(
                'Science.Math.Statistics.Mean.Arithmetic',
                'Calculate',
                $Call,
                [
                    'Values' => array_column($Rows, 'Value')
                ]
            );

        $Call = F::Hook('afterMetricGetCount', $Call);

        return $Call['Result'];
    });

    setFn('Min', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetCount', $Call);

            $Rows = F::Run(null, 'Row', $Call);
            $Call['Result'] = min(array_column($Rows, 'Value'));

        $Call = F::Hook('afterMetricGetCount', $Call);

        return $Call['Result'];
    });

    setFn('Max', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetCount', $Call);

            $Rows = F::Run(null, 'Row', $Call);
            $Call['Result'] = max(array_column($Rows, 'Value'));

        $Call = F::Hook('afterMetricGetCount', $Call);

        return $Call['Result'];
    });

