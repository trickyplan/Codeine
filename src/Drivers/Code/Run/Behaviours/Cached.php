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
        $Time = microtime(true); // Fix for stability
        
        if ($Keys = F::Dot($Call, 'Behaviours.Cached.Keys'))
        {
            $Hash = [];
            foreach ($Keys as $Key)
                $Hash[] = F::Dot($Call, $Key);
            
            $Scope = $Call['Run']['Service'].'_'.$Call['Run']['Method'];
            $CacheID = 'CID'.hash('sha256', j($Hash));
            
            // Try to get cached
            
            $Envelope = F::Run('IO', 'Read',
            [
                'Storage'   => 'Run Cache',
                'Scope'     => $Scope,
                'Where'     => ['ID' => $CacheID],
                'IO One'    => true
            ]);
            
            if ($Envelope === null) // No cached
                F::Log('Run cache *miss* for '.$Scope.':'.$CacheID, LOG_INFO, 'Performance');
            else
            {
                if (F::Dot($Envelope, 'Time')+F::Dot($Call, 'Behaviours.Cached.TTL') > $Time) // Not expired
                {
                    $Result = F::Dot($Envelope, 'Result');
                    $Run = false; // Hit
                    F::Log('Run cache *hit* for '.$Scope.':'.$CacheID, LOG_INFO, 'Performance');
                }
                else
                    F::Log('Run cache *expired* for '.$Scope.':'.$CacheID, LOG_INFO, 'Performance');
            }
            
            if ($Run)
            {
                $Result = F::Live($Call['Run']);

                $Envelope = [
                        'Time'      => $Time,
                        'Result'    => $Result
                    ];
                
                F::Run('IO', 'Write',
                [
                    'Storage'   => 'Run Cache',
                    'Scope'     => $Scope,
                    'Where'     => ['ID' => $CacheID],
                    'Data'      => $Envelope
                ]);
                
                F::Log('Run cache *stored* for '.$Scope.':'.$CacheID, LOG_INFO, 'Performance');
            }
        }
    
        $Call['Run']['Result'] = $Result;
        
        return $Call;
    });