<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        F::Log('Delayed Run '.$Call['Run']['Service'].' queued', LOG_INFO);

        return F::Run('IO', 'Write',
            [
                'Storage' => 'Delayed',
                'Data' => $Call['Run']
            ]);
     });

    setFn('Execute', function ($Call)
    {
        if ($Call['Run'] = F::Run('IO', 'Read', ['Time' => microtime(true), 'Storage' => 'Delayed']))
        {
            F::Log('Running delayed task', LOG_WARNING, 'Developer');
            return F::Live($Call['Run']);
        }
        else
            F::Log('No tasks', LOG_DEBUG, 'Developer');

        return null;
    });

    setFn('Count', function ($Call)
    {

        $Queued = F::Run('IO', 'Execute', ['Execute' => 'Count', 'Time' => microtime(true), 'Storage' => 'Delayed']);
        F::Log('Queued tasks: '.$Queued, LOG_INFO);

        return $Queued;
    });