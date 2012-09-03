<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        $Entries = F::Run('IO', 'Read',
        [
            'Storage' => 'Journal',
            'Sort' =>
                [
                    'Time' => SORT_DESC
                ]
        ]);

        foreach ($Entries as $Entry)
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Journal',
                    'ID' => 'Show/Control',
                    'Data' => $Entry
                ];

        return $Call;
    });

    self::setFn('Show', function ($Call)
    {
        $Entry = F::Run('IO', 'Read',
        [
            'Storage' => 'Journal',
            'Where' => $Call['Where']
        ])[0];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'Journal',
                'ID' => 'Show/Full',
                'Data' => $Entry
            ];


        return $Call;
    });

    self::setFn('Filter', function ($Call)
    {
        array_shift($Call['Request']);

        $Entries = F::Run('IO', 'Read',
        [
            'Storage' => 'Journal',
            'Where' => $Call['Request']
        ]);

        foreach ($Entries as $Entry)
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Journal',
                    'ID' => 'Show/Control',
                    'Data' => $Entry
                ];

        return $Call;
    });