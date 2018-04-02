<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Row', function ($Call)
    {
        $Call = F::Hook('beforeMetricGet', $Call);
            
            $Call = F::Apply('Metric.Calc', 'Where', $Call);
            
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
            
            $Call = F::Apply('Metric.Calc', 'Where', $Call);

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
