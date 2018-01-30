<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Run', function ($Call)
    {
        $Result = null;
        
        if ($Keys = F::Dot($Call, 'Behaviours.Delayed.Keys'))
        {
            $ReducedCall = [];
            
            foreach ($Keys as $Key)
                $ReducedCall = F::Dot($ReducedCall, $Key, F::Dot($Call, $Key));

            $Call['Run']['Call'] = $ReducedCall;

            $ResultID = hash('sha256', j($ReducedCall));
            
            $Result = F::Run('IO', 'Read',
            [
                'Storage'   => 'Delayed Outbox',
                'Where'     => ['ID' => $ResultID],
                'IO One'    => true
            ]);
            
            if ($Result === null)
            {
                F::Run('IO', 'Write',
                [
                    'Storage'   => 'Delayed Inbox',
                    'Scope'     => F::Dot($Call, 'Behaviours.Delayed.Priority'),
                    'Data'      =>
                    [
                        'ID' => $ResultID,
                        'Run' => $Call['Run']
                    ]
                ]);

                $Call['Run']['Skip'] = true;
                F::Log('Delayed Result '.$ResultID.' is *queued* with priority P'.F::Dot($Call, 'Behaviours.Delayed.Priority'), LOG_INFO, 'Performance');
            }
            else
            {
                F::Run('IO', 'Write',
                [
                    'Storage'   => 'Delayed Outbox',
                    'Where'     => ['ID' => $ResultID],
                    'IO One'    => true,
                    'Data'      => null
                ]);
                F::Log('Delayed Result '.$ResultID.' is *ready* with priority P'.F::Dot($Call, 'Behaviours.Delayed.Priority').' and purged', LOG_INFO, 'Performance');
            }
        }
        
        $Call['Run']['Result'] = $Result;
        
        return $Call;
    });
    
    setFn('Run from Queue', function ($Call)
    {
        $Result = null;
        
        $Envelope = F::Run('IO', 'Read',
            [
                'Storage'   => 'Delayed Inbox',
                'IO One'    => true,
                'Scope'     => F::Dot($Call, 'Behaviours.Delayed.Priority')
            ]);
        
        if ($Envelope === null)
            F::Log('Queue P'.F::Dot($Call, 'Behaviours.Delayed.Priority').' is empty', LOG_INFO, 'Performance');
        else
        {
            $Result = F::Live($Envelope['Run'], $Call);
            F::Log('Delayed Result '.$Envelope['ID'].' is *executed* with priority P'.F::Dot($Call, 'Behaviours.Delayed.Priority'), LOG_INFO, 'Performance');
            
            F::Run('IO', 'Write',
                    [
                        'Storage'   => 'Delayed Outbox',
                        'Where'     => ['ID' => $Envelope['ID']],
                        'Data'      => $Result
                    ]);
        }
        
        return $Result;
    });