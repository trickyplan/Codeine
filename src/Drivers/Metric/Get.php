<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Row', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetRow', $Call);

            $Call['Result'] = F::Run('IO', 'Read',
            [
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where!'    => $Call['Metric']['Where']
            ]);

        $Call = F::Hook('afterMetricGetRow', $Call);
        
        return $Call['Result'];
    });
    
    setFn('Last', function ($Call)
    {
        $Call = F::Hook('beforeMetricGetLast', $Call);
            
            $Call['Result'] = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where!'     => $Call['Metric']['Where'],
                'Sort'      =>
                [
                    'Metric.Time' => false
                ],
                'IO One'    => true
            ]);
        
        $Call = F::Hook('afterMetricGetLast', $Call);

        return F::Dot($Call, 'Result.Value');
    });
