<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Spit', function ($Call)
    {
        F::Log('NC: '.F::$NC, LOG_INFO);

        $Logs = F::Logs();

        if (!empty($Logs))
        {
            F::Run(
                'IO', 'Write',
                [
                    'Renderer' => $Call['Renderer'],
                    'Storage' => 'Developer',
                    'ID' => 'Crash Report from '.$Call['Host'].$Call['URL'],
                    'Data' => $Logs
                ]
            );

            F::Run('IO', 'Close', array('Storage' => 'Developer'));
        }

        return $Call;
    });