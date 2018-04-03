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
        $Call['Metric'] = F::Live($Call['Metric'], $Call);
      
        if (empty(F::Dot($Call, 'Metric.Event.Time.Exact')))
            $Call = F::Dot($Call, 'Metric.Event.Time', F::Run('System.Time', 'Get', $Call,
                [
                    'Time' =>
                    [
                        'Offset' => F::Dot($Call, 'Metric.Event.Time.Offset')
                    ]
                ]));
        
        F::Run('IO', 'Write', $Call,
            [
                'Storage'   => 'Metric Queue',
                'Scope'     => F::Dot($Call, 'Metric.Event.Type'),
                'Data'      => F::Dot($Call, 'Metric.Event')
            ]);

        $Call = F::Dot($Call, 'Metric.Event', null);
        
        return $Call;
    });
    
    setFn('Aggregate', function ($Call)
    {
        $Call['Result'] = [];
        $Type = F::Dot($Call, 'Metric.Event.Type');
        
        $Count = F::Run('IO', 'Execute', $Call,
            [
                'Execute'   => 'Count',
                'Storage'   => 'Metric Queue',
                'Scope'     => $Type
            ]);
        
        F::Log('Queue Size: '.$Count, LOG_NOTICE);
        
        if (F::Dot($Call, 'Metric.Aggregate.Batch.AutoSize'))
            $Call = F::Dot($Call, 'Metric.Aggregate.Batch.Size', $Count);
        
        // Read Event from Queue
        $Events = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Metric Queue',
                'Scope'     => $Type,
                'Limit'     =>
                [
                    'From'  => 0,
                    'To'    => F::Dot($Call, 'Metric.Aggregate.Batch.Size')
                ]
            ]);
        
        $Aggregate = [];
        
        if (empty($Events))
            ;
        else
        {
            foreach ($Events as $Event)
            {
                if (isset($Event['Dimensions']))
                    $Where = $Event['Dimensions'];
                else
                    $Where = [];
                
                $Where ['Type'] = $Type;
                
                foreach ($Call['Metric']['Event']['Resolutions'] as $Call['Metric']['Event']['Resolution'])
                {
                    $Where ['Time'] = floor($Event['Time'] / $Call['Metric']['Event']['Resolution']);
                    $Where ['Resolution'] = $Call['Metric']['Event']['Resolution'];
                    
                    $HashedWhere = hash('sha256', serialize($Where));
        
                    if (isset($Event['Value']))
                        ;
                    else
                        $Event['Value'] = 1;
                    
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
                    if (is_numeric($Row['Value']))
                        $Call['Data']['Value'] += $Row['Value'];
                    
                    F::Run('IO', 'Write', $Call,
                    [
                        'Storage'   => 'Primary',
                        'Scope'     => 'Metric',
                        'Where'     => $Row['Where'] // Data implied
                    ]);
                }
                
                $Call['Result'][] = $Call['Data'];
            }
        }
        
        return $Call;
    });
    
    setFn('Add.Front', function ($Call)
    {
        $Event['Type'] = F::Dot($Call, 'Request.Type');
        $Event['Dimensions'] = F::Dot($Call, 'Request.Dimensions');
        
        if (empty($Event['Type']))
            ;
        else
            F::Run(null, 'Add', $Call,
                [
                    'Metric'    =>
                    [
                        'Event' => $Event
                    ]
                ]);
        
        $Call['Output']['Content'] = j($Event);
        return $Call;
    });