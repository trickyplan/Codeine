<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Journal', 'Template' => 'Control', 'PageURL' => '/control/Journal/page', 'Context' => 'app'),
            $Call);
    });

    setFn('Show', function ($Call)
    {
        $Entry = F::Run('Entity', 'Read',
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

    setFn('Filter', function ($Call)
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