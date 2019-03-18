<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
                    .DS.RequestID,
                'Data'      => $Call
            ]);

        return $Call;
    });