<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return F::Run('IO', 'Write', $Call,
        [
            'Storage' => 'Monitor',
            'Scope' => 'Status',
            'Data' => $Call['Status']
        ]);
    });