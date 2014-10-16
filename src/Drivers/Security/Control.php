<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Security',
            'ID' => 'Status'
        ];

        return $Call;
    });