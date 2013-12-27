<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Receiver'] = F::Run('Entity', 'Read', $Call,
        [
            'Entity' => 'Company',
            'Where'  => 1,
            'One'    => true
        ]);

        $Call['Output']['Content'][] =
        [
            'Type'  => 'Template',
            'Scope' => 'Payment',
            'ID'    => 'Russia/Wire',
            'Data'  => $Call['Data']
        ];

        return $Call;
    });