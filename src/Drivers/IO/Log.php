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

        foreach ($Logs as $Log)
        {
            F::Run(
                'IO', 'Write',
                [
                    'Renderer' => $Call['Renderer'],
                    'Storage' => 'Developer',
                    'Scope' => $Call['Project']['ID'],
                    'Data' => $Log
                ]
            );
        }

        F::Run('IO', 'Close', array('Storage' => 'Developer'));

        return $Call;
    });