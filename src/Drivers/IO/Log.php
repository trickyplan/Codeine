<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Spit', function ($Call)
    {
        F::Log('Calls: '.F::$NC, LOG_INFO);
        F::Log('Memory: '.round(memory_get_usage()/1024).' KiB', LOG_INFO);

        $Logs = F::Logs();

        if (!empty($Logs))
        {
            // $Logs = array_reverse($Logs);
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