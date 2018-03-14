<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 12.x
     */
    
    setFn('Add', function ($Call)
    {
        // Add Event to Metric Queue
        $Call = F::Dot($Call, 'Metric.Event.Time', F::Run('System.Time', 'Get', $Call));
        
        F::Run('IO', 'Write', $Call,
            [
                'Storage'   => 'Metric Queue',
                'Scope'     => F::Dot($Call, 'Metric.Event.Type'),
                'Data'      => F::Dot($Call, 'Metric.Event')
            ]);
        
        return true;
    });
    
    setFn('Aggregate', function ($Call)
    {
        $Result = [];
        $Type = F::Dot($Call, 'Metric.Event.Type');
        
        // Read Event from Queue
        $Events = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Metric Queue',
                'Scope'     => $Type,
                'Limit'     =>
                [
                    'From'  => 0,
                    'To'    => F::Dot($Call, 'Metric.Aggregate.Batch Size')
                ]
            ]);
        
        $Aggregate = [];
        
        if (empty($Events))
            ;
        else
        {
            foreach ($Events as $Event)
            {
                $Where = $Event['Dimensions'];
                $Where ['Type'] = $Type;
                
                foreach ($Call['Metric']['Event']['Resolutions'] as $Call['Metric']['Event']['Resolution'])
                {
                    $Where ['Time'] = floor($Event['Time'] / $Call['Metric']['Event']['Resolution']);
                    $Where ['Resolution'] = $Call['Metric']['Event']['Resolution'];
                    
                    $HashedWhere = hash('sha256', serialize($Where));
        
                    if (isset($Aggregate[$HashedWhere]))
                        $Aggregate[$HashedWhere]['Value'] += $Event['Value'];
                    else
                    {
                        $Aggregate[$HashedWhere]['Value'] = $Event['Value'];
                        $Aggregate[$HashedWhere]['Where'] = $Where;
                    }
                }
            }
            
            foreach ($Aggregate as $Row)
            {
                $Call['Data'] = F::Run('IO', 'Read', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Where'     => $Row['Where'],
                        'IO One'    => true
                    ]);
                
                if (empty($Call['Data']))
                {
                    $Call['Data'] = $Row['Where'];
                    $Call['Data']['Value'] = $Row['Value'];
                    
                    F::Run('IO', 'Write', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                    ]);
                }
                else
                {
                    $Call['Data']['Value'] += $Row['Value'];
                    
                    F::Run('IO', 'Write', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Where'     => $Row['Where'] // Data implied
                    ]);
                }
                
                $Result[] = $Call['Data'];
            }
        }
        
        return $Result;
    });