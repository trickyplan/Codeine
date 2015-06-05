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

        if (isset ($Call['Run']['CacheID'])
            && isset($Call['Run']['RTTL'])
            && $Call['Run']['RTTL'] > 0)
        {
            $RTTL = $Call['Run']['RTTL'];
            unset($Call['Run']['RTTL']);

            $Scope = $Call['Run']['Service'].DS.$Call['Run']['Method'];

            $Envelope = F::Run('IO', 'Read',
                [
                    'Storage' => 'Run Cache',
                    'No Memo'  => true,
                    'Scope'   => $Scope,
                    'Where' => ['ID' => $Call['Run']['CacheID']]
                ]);

            $Memo = serialize($Call['Run']['Memo']);

            if ($Envelope !== null)
            {
                if ($Envelope[0]['Expire'] > time())
                {
                    F::Log('Cache *hit* for call '.$Scope.'('.$Memo.')', LOG_INFO, 'Performance');

                    $Run = false;
                    $Result = $Envelope[0]['Result'];
                }
                else
                    F::Log('Cache *expired* for call '.$Scope.'('.$Memo.')', LOG_INFO, 'Performance');
            }
            else
                F::Log('Cache *miss* for call '.$Scope.'('.$Memo.')', LOG_INFO, 'Performance');

            if ($Run)
            {
                $Result = F::Live($Call['Run'], ['No Memo' => true]);

                if ($Result === null)
                    $Result = $Envelope[0]['Result'];
                else
                {
                    F::Log('Cache *stored* for call '.$Scope.'('.$Memo.')', LOG_INFO, 'Performance');
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
        }
        else
        {
            if (isset($Call['Run']['RTTL']) && !isset ($Call['Run']['CacheID']))
                F::Log('RTTL defined, but contract is not', LOG_WARNING, 'Developer');

            $Result = F::Live($Call['Run']);
        }

        return $Result;
     });