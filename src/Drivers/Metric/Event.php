<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 12.x
     */
    
    setFn('Add', function ($Call)
    {
        $Call['Metric'] = F::Live($Call['Metric'], $Call);

        if (F::Dot($Call, 'Metric.DryRun') == true)
            ;
        else
        {
            // Add Event to Metric Queue
          
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
            
            
            F::Log(function () use ($Call) {return 'Metric Event: '.F::Dot($Call, 'Metric.Event.Type').' '.j(F::Dot($Call, 'Metric.Event'));} , LOG_INFO, 'Metric');
            
            $Call = F::Dot($Call, 'Metric.Event', null);
        }
        
        return $Call;
    });
    
    setFn('Aggregate', function ($Call)
    {
        $VCall = $Call;
        
        $VCall['Result'] = [];
        $Type = F::Dot($VCall, 'Metric.Event.Type');
        
        $Count = F::Run('IO', 'Execute', $VCall,
            [
                'Execute'   => 'Count',
                'Storage'   => 'Metric Queue',
                'Scope'     => $Type
            ]);
        
        F::Log('Queue Size: '.$Count, LOG_NOTICE);
        
        if (F::Dot($VCall, 'Metric.Aggregate.Batch.AutoSize'))
            $VCall = F::Dot($VCall, 'Metric.Aggregate.Batch.Size', $Count);
        
        // Read Event from Queue
        
        if ($Count > 0)
        {
            $Call = F::Hook($Type.'.Event.Aggregate.Before', $Call);
           
            $Events = F::Run('IO', 'Read', $VCall,
                [
                    'Storage'   => 'Metric Queue',
                    'Scope'     => $Type,
                    'Where!'     => null,
                    'Limit'     =>
                    [
                        'From'  => 0,
                        'To'    => F::Dot($VCall, 'Metric.Aggregate.Batch.Size')
                    ]
                ]);

            $Call['Aggregate'] = [];
            
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
                    
                    foreach ($VCall['Metric']['Event']['Resolutions'] as $VCall['Metric']['Event']['Resolution'])
                    {
                        $Where ['Time'] = floor($Event['Time'] / $VCall['Metric']['Event']['Resolution']);
                        $Where ['Resolution'] = $VCall['Metric']['Event']['Resolution'];
                        
                        $HashedWhere = hash('sha256', serialize($Where));
            
                        if (isset($Event['Value']))
                        {
                            if (is_numeric($Event['Value']))
                                ;
                            else
                                $Event['Value'] = 1;
                        }
                        else
                            $Event['Value'] = 1;
                        
                        if (isset($Call['Aggregate'][$HashedWhere]))
                            $Call['Aggregate'][$HashedWhere]['Value'] += $Event['Value'];
                        else
                        {
                            $Call['Aggregate'][$HashedWhere]['Value'] = $Event['Value'];
                            $Call['Aggregate'][$HashedWhere]['Where'] = $Where;
                        }
                    }
                }
                
                foreach ($Call['Aggregate'] as $Row)
                {
                    $VCall['Data'] = F::Run('IO', 'Read', $VCall,
                        [
                            'Storage'   => 'Primary',
                            'Scope'     => 'Metric',
                            'Where'     => $Row['Where'],
                            'IO One'    => true
                        ]);
                    
                    if (empty($VCall['Data']))
                    {
                        $VCall['Data'] = $Row['Where'];
                        $VCall['Data']['Value'] = $Row['Value'];
                        
                        F::Run('IO', 'Write', $VCall,
                        [
                            'Storage'   => 'Primary',
                            'Scope'     => 'Metric',
                        ]);
                    }
                    else
                    {
                        if (is_numeric($Row['Value']))
                            $VCall['Data']['Value'] += $Row['Value'];
                        
                        F::Run('IO', 'Write', $VCall,
                        [
                            'Storage'   => 'Primary',
                            'Scope'     => 'Metric',
                            'Where'     => $Row['Where'] // Data implied
                        ]);
                    }
                    
                    // $VCall['Event Result'][] = $VCall['Data'];
                }
                
                $Call = F::Hook($Type.'.Event.Aggregate.After', $Call);
            }
        }
        
        return $Call;
    });
    
    setFn('Add.Front', function ($Call)
    {
        if (F::Dot($Call, 'Metric.DryRun'))
            ;
        else
        {
            $Call['Event']['Type'] = F::Dot($Call, 'Request.Type');
            $Call['Event']['Dimensions'] = F::Dot($Call, 'Request.Dimensions');
            
            $Call = F::Hook($Call['Event']['Type'].'.Event.AddFront.Before', $Call);
            
            if (empty($Call['Event']['Type']))
                ;
            else
            {
                if (in_array($Call['Event']['Type'], F::Dot($Call, 'Metric.Front.Types.Allowed')))
                    F::Run(null, 'Add', $Call,
                        [
                            'Metric' =>
                                [
                                    'Event' => $Call['Event']
                                ]
                        ]);
            }
    
            $Call['Output']['Content'] = j($Call['Event']);

            $Origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';

            $Call['HTTP']['Headers']['Access-Control-Allow-Origin: '] = $Origin;
            $Call['HTTP']['Headers']['Access-Control-Allow-Credentials:'] = 'true';
        }
        return $Call;
    });