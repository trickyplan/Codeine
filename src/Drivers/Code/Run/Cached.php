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

        if (isset ($Call['Run']['CacheID'])
            && isset($Call['Run']['RTTL'])
            && $Call['Run']['RTTL'] > 0
            && isset($Call['Run Cache Enabled'])
            && $Call['Run Cache Enabled'])
        {
            $RTTL = $Call['Run']['RTTL'];
            unset($Call['Run']['RTTL']);

            $Scope = $Call['Run']['Service'].DS.$Call['Run']['Method'];

            $Envelope = F::Run('IO', 'Read',
                [
                    'Storage' => 'Run Cache',
                    'Scope'   => $Scope,
                    'Where' => ['ID' => $Call['Run']['CacheID']]
                ]);

            if ($Envelope !== null)
            {
                if ($Envelope[0]['Expire'] > time())
                {
                    F::Log('Cache *hit* for call '.$Call['Run']['Service'].':'.$Call['Run']['Method'].'('.j($Call['Run']['Memo']).')', LOG_INFO, 'Performance');

                    $Run = false;
                    $Result = $Envelope[0]['Result'];
                }
                else
                    F::Log('Cache *expired* for call '.$Call['Run']['Service'].':'.$Call['Run']['Method'].'('.j($Call['Run']['Memo']).')', LOG_INFO, 'Performance');
            }
            else
                F::Log('Cache *miss* for call '.$Call['Run']['Service'].':'.$Call['Run']['Method'].'('.j($Call['Run']['Memo']).')', LOG_DEBUG, 'Performance');

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
                        'Where'   => ['ID' => $Call['Run']['CacheID']],
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