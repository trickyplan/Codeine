<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        if (F::Run('Code.Flow.Daemon', 'Running?', $Call) || $Call['Delayed Mode'] == 'Dirty')
            return F::Run('IO', 'Write', array
                 (
                    'Storage' => 'Run Queue',
                    'Scope' => 'RQ',
                    'Data' => $Call['Run']
                 ));
        else
            return F::Live($Call['Run']);
     });

    setFn('Execute', function ($Call)
    {
        return F::Live($Call['Run']);
    });

    setFn('Queue', function ($Call)
    {
        return F::Run('IO', 'Read', ['Storage' => 'Run Queue', 'Scope' => 'RQ']);
    });