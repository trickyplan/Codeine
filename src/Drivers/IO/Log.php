<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Spit', function ($Call)
    {
        if (self::$_Environment != 'Production')
        {
            F::Run(
                'IO', 'Write',
                [
                    'Renderer' => $Call['Renderer'],
                    'Storage' => 'Developer',
                    'Data' => F::Logs()
                ]
            );

            F::Run('IO', 'Close', array('Storage' => 'Developer'));
        }


        return $Call;
    });