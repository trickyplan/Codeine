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
                'Queue'   => $Call['Priority'],
                'Data' => $Call['Run']
            ]);
     });

    setFn('Execute', function ($Call)
    {
        foreach ($Call['Priorities'] as $Call['Priority'])
        {
            if ($Call['Run'] = F::Run('IO', 'Read', ['Time' => microtime(true), 'Queue' => $Call['Priority'], 'Storage' => 'Delayed']))
            {
                F::Log('Running delayed task', LOG_WARNING, 'Developer');
                return F::Live($Call['Run']);
            }
            else
                F::Log('No tasks at '.$Call['Priority'], LOG_DEBUG, 'Developer');
        }

        return null;
    });

    setFn('Count', function ($Call)
    {
        foreach ($Call['Priorities'] as $Call['Priority'])
        {
            $Queued[$Call['Priority']] = F::Run('IO', 'Execute', ['Execute' => 'Count', 'Time' => microtime(true), 'Storage' => 'Delayed', 'Queue' => $Call['Priority']]);
            F::Log('Queued '.$Call['Priority'].' priority tasks: '.$Queued[$Call['Priority']], LOG_INFO);
        }

        return implode('/', $Queued);
    });