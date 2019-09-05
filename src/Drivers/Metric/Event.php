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

        if (F::Dot($Call, 'Metric.DryRun') === true)
            ;
        else
        {
            // Add Event to Metric Queue

            if ($MetricTime = F::Dot($Call, 'Metric.Event.Time.Exact') == null)
            {
                $MetricTime = F::Run('System.Time', 'Get', $Call,
                    [
                        'Time' =>
                        [
                            'Offset' => F::Dot($Call, 'Metric.Event.Time.Offset')
                        ]
                    ]);
                $Call = F::Dot($Call, 'Metric.Event.Time', $MetricTime);
            }

            F::Log('Event Time: '.date(DATE_W3C, $MetricTime), LOG_INFO);

            F::Run('IO', 'Write', $Call,
                [
                    'Storage'   => 'Metric Queue',
                    'Scope'     => F::Dot($Call, 'Metric.Event.Type'),
                    'Data!'      => F::Dot($Call, 'Metric.Event')
                ]);
            
            
            F::Log(function () use ($Call) {return 'Metric Event: '.F::Dot($Call, 'Metric.Event.Type').' '.j(F::Dot($Call, 'Metric.Event'));} , LOG_INFO, 'Administrator');
            
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
        
        F::Log('Metric Queue *'.$Type.'* has *'.$Count.'* elements', LOG_NOTICE);
        
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
                $DT = new DateTime();
                foreach ($Events as $Event)
                {
                    if (isset($Event['Dimensions']))
                        $Where = $Event['Dimensions'];
                    else
                        $Where = [];
                    
                    $Where ['Type'] = $Type;
                    $TZ = new DateTimeZone(F::Dot($Call, 'Metric.Aggregate.Timezone'));

                    $DT->setTimestamp($Event['Time']);
                    $DT->setTimezone($TZ);

                    foreach ($VCall['Metric']['Event']['Resolutions'] as $VCall['Metric']['Event']['Resolution'])
                    {
                        $Event['Time'] += $DT->getOffset()-1;

                        F::Log(function () use ($DT) {$DT->format('Y.m.d H:i:s');}, LOG_DEBUG);
                        F::Log($Event['Time'], LOG_DEBUG);

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

                        unset($VCall['Where']);
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

                    F::Log('Event: *'.$Row['Where']['Type'].'*. Resolution: *'.$Row['Where']['Resolution'].'*. Value: *'.$VCall['Data']['Value'].'*', LOG_NOTICE);
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

            if (in_array($Call['Event']['Type'], F::Dot($Call, 'Metric.Front.Types.Allowed')))
            {
                if (in_array(F::Dot($Call, 'HTTP.Method'), F::Dot($Call, 'Metric.Front.Methods.Allowed')))
                {
                    $Call['Event']['Dimensions'] = F::Dot($Call, 'Request.Dimensions');

                    $Call = F::Hook($Call['Event']['Type'].'.Event.AddFront.Before', $Call);

                    if (empty($Call['Event']['Type']))
                        ;
                    else
                    {
                        F::Run(null, 'Add', $Call,
                            [
                                'Metric' =>
                                    [
                                        'Event' => $Call['Event']
                                    ]
                            ]);
                    }

                    $Call['Output']['Content'] = $Call['Event'];
                }
                else
                    $Call['Output']['Content'] = F::Log('Incorrect HTTP Method', LOG_NOTICE);
            }
            else
                $Call['Output']['Content'] = F::Log('Incorrect Metric Type', LOG_NOTICE);
        }
        return $Call;
    });