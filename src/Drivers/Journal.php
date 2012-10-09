<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Write', function ($Call)
    {
        F::Run('IO', 'Write',
        [
            'Storage' => 'Journal',
            'Scope'  => $Call['Host'],
            'Data'   => F::Merge($Call,
                [
                    'ID'    => F::Run('Security.UID', 'Get', ['Mode' => $Call['Journal']['UID']]),
                    'Time'   => F::Run('System.Time', 'Get', ['Mode' => $Call['Journal']['Timer']])
                ])
        ]);

        return $Call;
    });

