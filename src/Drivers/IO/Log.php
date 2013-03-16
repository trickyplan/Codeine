<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Spit', function ($Call)
    {
        $Logs = F::Logs();

        if (!empty($Logs))
        {
            F::Run(
                'IO', 'Write',
                [
                    'Renderer' => $Call['Renderer'],
                    'Storage' => 'Developer',
                    'ID' => $Call['Host'].$Call['URL'],
                    'Data' => $Logs,
                    'RAW' => $Logs
                ]
            );

            F::Run('IO', 'Close', array('Storage' => 'Developer'));
        }

        return $Call;
    });