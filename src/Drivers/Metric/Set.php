<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeMetricSet', $Call);
        
            $Call['Result'] = [];
            $Time = F::Run('System.Time', 'Get', $Call);
            
            $Call['Where'] = $Call['Metric']['Dimensions'];
            F::Log(function () use ($Call) {return 'Metric Dimensions: '.j($Call['Where']);} , LOG_INFO);
            
            $Call['Where']['Type'] = $Call['Metric']['Type'];
            
            F::Log(function () use ($Call) {return 'Metric Type: '.$Call['Where']['Type'];} , LOG_INFO);
            
            F::Log(function () use ($Call) {return 'Resolutions: '.j($Call['Metric']['Resolutions']);} , LOG_INFO);
            
            foreach ($Call['Metric']['Resolutions'] as $Call['Metric']['Resolution'])
            {
                $Call = F::Hook('beforeMetricResolutionProcess', $Call);
                
                $Call['Data'] = $Call['Where'];
                $Call['Where']['Time'] = floor($Time / $Call['Metric']['Resolution']);
                $Call['Where']['Resolution'] = $Call['Metric']['Resolution'];
                
                F::Log(function () use ($Call, $Time) {return 'Metric Time: '.$Call['Where']['Time'].' with resolution '.$Call['Where']['Resolution'];} , LOG_INFO);
    
                $Call['Data'] = F::Run('IO', 'Read', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Where'     => $Call['Where'],
                        'IO One'    => true
                    ]);
                
                if (empty($Call['Data']))
                {
                    F::Log(function () use ($Call) {return 'Metric '.j($Call['Where']).' is empty';} , LOG_INFO);
                    $Call['Data'] = $Call['Where'];
                    $Call['Data']['Value'] = $Call['Metric']['Value'];
    
                    unset ($Call['Where']);
                    $Call['Result']['R'.$Call['Metric']['Resolution']] = F::Run('IO', 'Write', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Data'      => $Call['Data']
                    ]);
                }
                else
                {
                    F::Log(function () use ($Call) {return 'Metric '.j($Call['Where']).' isn\'t empty';} , LOG_INFO);
                    $Call['Data']['Value'] = $Call['Metric']['Value'];
                    
                    $Call['Result']['R'.$Call['Metric']['Resolution']] = F::Run('IO', 'Write', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Where'     => $Call['Where'],
                        'Data'      => $Call['Data']
                    ]);
                }
                
                $Call = F::Hook('afterMetricResolutionProcess', $Call);
            }
        
        $Call = F::Hook('afterMetricSet', $Call);
        
        return $Call['Result'];
    });