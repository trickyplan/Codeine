<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Write', function ($Call)
    {
        F::Run('IO', 'Write',
        [
            'Storage' => 'Journal',
            'Scope'  => 'Journal',
            'Data'   =>
                [
                    'ID'    => F::Run('Security.UID', 'Get', ['Mode' => $Call['Journal']['UID']]),
                    'Time'   => F::Run('System.Time', 'Get', ['Mode' => $Call['Journal']['Timer']]),
                    'Entity' => $Call['Entity'],
                    'Event' => $Call['Event'],
                    'User' => $Call['Session']['User']['ID']
                ]
        ]);

        return $Call;
    });

