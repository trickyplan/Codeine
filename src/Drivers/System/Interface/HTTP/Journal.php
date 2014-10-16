<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        F::Run('IO', 'Write',
            [
                'Storage'   => 'HTTP Journal',
                'Scope'     => 'journal',
                'Where'     => date('Y')
                    .DS.date('m')
                    .DS.date('d')
                    .DS.date('H')
                    .DS.date('i')
                    .DS.date('s')
                    .DS.$Call['HTTP']['IP']
                    .DS.REQID,
                'Data'      => $Call
            ]);

        return $Call;
    });