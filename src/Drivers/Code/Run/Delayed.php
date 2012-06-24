<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Run', function ($Call)
    {
        return F::Run('IO', 'Write', array
                             (
                                'Storage' => 'Run Queue',
                                'Scope' => '',
                                'Data' => $Call['Run']
                             ));
     });

    self::setFn('Flush', function ($Call)
    {

        while(($Run = F::Run('IO', 'Read', array
                              (
                                 'Storage' => 'Run Queue',
                                 'Scope' => ''
                              ))) !== null)
            F::Live($Run);


        return $Call;
    });