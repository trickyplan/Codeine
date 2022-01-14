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
        $Run = true;
        $Time = microtime(true); // Fixed for stability
        
        if ($Keys = F::Dot($Call, 'Behaviours.Cached.Keys'))
        {
            $Hash = [$Call['Run']['Service'], $Call['Run']['Method']];
            foreach ($Keys as $Key)
                $Hash[] = F::Dot($Call['Run']['Call'], $Key);
            
            $sHash = serialize($Hash);
            $jHash = j($Hash);
            $cHash = F::Run('Security.Hash', 'Get', $Call,
                [
                    'Security' =>
                    [
                        'Hash' =>
                        [
                            'Mode'  => 'Secure'
                        ]
                    ],
                    'Value' => $sHash
                ]);

            $CacheID = implode('.',
                [
                    F::Dot($Call, 'Behaviours.Cached.Hash.Algo'),
                    $cHash
                ]);
            
            // Try to get cached

            $Envelope = F::Run('IO', 'Read',
            [
                'Storage'   => F::Dot($Call, 'Behaviours.Cached.Result.Storage'),
                'Where'     => ['ID' => $CacheID],
                'IO One'    => true
            ]);

            if ($Envelope === null) // No cached result
                F::Log('Cache *miss* for '.$jHash, LOG_NOTICE, 'Performance');
            else
            {
                if (F::Dot($Envelope, 'Time')+F::Dot($Call, 'Behaviours.Cached.TTL') > $Time) // Not expired
                {
                    $Result = F::Dot($Envelope, 'Result');
                    $Run = false; // Hit
                    F::Log('Cache *hit* for '.$jHash, LOG_NOTICE, 'Performance');
                }
                else
                    F::Log('Cache *expired* for '.$jHash, LOG_NOTICE, 'Performance');
            }

            if ($Run)
            {
                $TTL = F::Dot($Call, 'Behaviours.Cached.TTL');

                $Call = F::Dot($Call, 'Behaviours.Cached', null);
                $Result = F::Live($Call['Run']);

                if (empty($Result) && F::Dot($Call, 'Behaviours.Cached.Result.Allow Storing Empty') == false)
                    F::Log('Cache *skipped* for '.$jHash.' because it\'s empty and "Allow Storing Empty" is false', LOG_NOTICE, 'Performance');
                else
                {
                    $Envelope = [
                        'Time'      => $Time,
                        'Result'    => $Result
                    ];

                    F::Run('IO', 'Write',
                    [
                        'Storage'   => 'Cache',
                        'Where'     => ['ID' => $CacheID],
                        'Data'      => $Envelope
                    ]);

                    F::Log('Cache *expired* for '.$jHash.' with TTL '.$TTL, LOG_NOTICE, 'Performance');
                }

            }
        }
    
        $Call['Run']['Result'] = $Result;
        
        return $Call;
    });