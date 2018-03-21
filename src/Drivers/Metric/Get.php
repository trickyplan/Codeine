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
        
            $Call['Where'] = $Call['Metric']['Dimensions'];
            F::Log(function () use ($Call) {return 'Metric Dimensions: *'.j($Call['Where']).'*';} , LOG_INFO);
            
            $Call['Where']['Type'] = $Call['Metric']['Type'];
            F::Log(function () use ($Call) {return 'Metric Type: *'.$Call['Where']['Type'].'*';} , LOG_INFO);
            
            
            $Call['Where']['Resolution'] = $Call['Metric']['Resolutions'];
            F::Log(function () use ($Call) {return 'Resolutions: *'.j($Call['Metric']['Resolutions']).'*';} , LOG_INFO);
            
            $Call['Result'] = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Primary',
                'Scope'     => 'Metric',
                'Where'     => $Call['Where']
            ]);
        
        $Call = F::Hook('afterMetricGet', $Call);
        
        return $Call['Result'];
    });
    
    setFn('Sum', function ($Call)
    {
        $Rows = F::Run(null, 'Row', $Call);
        $Values = array_column($Rows, 'Value');
        
        $Sum = array_sum($Values);
        return $Sum;
    });