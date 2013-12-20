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

        $CacheID = sha1(json_encode($Call['Run']));

        $Envelope = F::Run('IO', 'Read',
            [
                'Storage' => 'Run Cache',
                'Where' => ['ID' => $CacheID]
            ]);

        if ($Envelope !== null && $Envelope[0]['Expire'] > time())
        {
            F::Log('Found good cache for '.$Call['Run']['Service'], LOG_GOOD, 'Developer');

            $Run = false;
            $Result = $Envelope[0]['Result'];
        }

        if ($Run)
        {
            $Result = F::Live($Call['Run']);

            F::Run('IO', 'Write',
            [
                'Storage' => 'Run Cache',
                'Scope'   => 'Run',
                'Where'   => ['ID' => $CacheID],
                'Data'    =>
                [
                    'Result' => $Result,
                    'Expire' => time()+$Call['TTL']
                ]
            ]);
        }

        return $Result;
     });