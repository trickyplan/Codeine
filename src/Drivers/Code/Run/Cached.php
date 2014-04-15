<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Result = null;
        $Run = true;

        if (isset($Call['Run']['RTTL']) && isset($Call['Run Cache Enabled']) && $Call['Run Cache Enabled'])
        {
            $RTTL = $Call['Run']['RTTL'];
            unset($Call['Run']['RTTL']);

            $CacheID = sha1(json_encode($Call['Run']));
            $Scope = $Call['Run']['Service'].DS.$Call['Run']['Method'];

            $Envelope = F::Run('IO', 'Read',
                [
                    'Storage' => 'Run Cache',
                    'Scope'   => $Scope,
                    'Where' => ['ID' => $CacheID]
                ]);

            if ($Envelope !== null && $Envelope[0]['Expire'] > time())
            {
                F::Log('Found good cache for '.$CacheID, LOG_INFO, 'Developer');

                $Run = false;
                $Result = $Envelope[0]['Result'];
            }

            if ($Run)
            {
                $Result = F::Live($Call['Run']);

                if ($Result === null)
                    $Result = $Envelope[0]['Result'];
                else
                    F::Run('IO', 'Write',
                    [
                        'Storage' => 'Run Cache',
                        'Scope'   => $Scope,
                        'Where'   => ['ID' => $CacheID],
                        'Data'    =>
                        [
                            'Result' => $Result,
                            'Expire' => time()+$RTTL
                        ]
                    ]);
            }
        }
        else
            $Result = F::Live($Call['Run']);

        return $Result;
     });