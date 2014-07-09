<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Running = F::Run('Code.Flow.Daemon', 'Running?',
                [
                    'Execute' =>
                    [
                        'Service' => 'Code.Run.Delayed'
                    ]
                ]);

        if ($Running)
            F::Log('Daemon running', LOG_INFO, 'Developer');

        if ($Running or $Call['Delayed Mode'] == 'Dirty')
        {
            F::Log('Delayed Run '.$Call['Run']['Service'].' queued', LOG_INFO);

            return F::Run('IO', 'Write',
                [
                    'Storage' => 'Delayed',
                    'Data' => $Call['Run']
                ]);
        }
        else
        {
            F::Log('Delayed Run '.$Call['Run']['Service'].' executed', LOG_INFO);
            return F::Live($Call['Run']);
        }
     });

    setFn('Execute', function ($Call)
    {
        if ($Call['Run'] = F::Run('IO', 'Read', ['Time' => microtime(true), 'Storage' => 'Delayed']))
        {
            F::Log('Running delayed task', LOG_WARNING, 'Developer');
            return F::Live($Call['Run']);
        }

        return null;
    });