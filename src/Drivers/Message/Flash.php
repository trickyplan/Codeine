<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Message = F::Run('Entity', 'Read',
            [
                'Entity' => 'Message',
                'One'    => true,
                'Where'  =>
                [
                    'Target' => $Call['Session']['User']['ID']
                ]
            ]
        );

        if (!empty($Message))
            $Call['Output']['Content'][] =
                [
                    'Type'  => 'Template',
                    'Scope' => 'Message',
                    'ID'    => 'Show/Flash',
                    'Data'  => $Message
                ];

        return $Call;
    });