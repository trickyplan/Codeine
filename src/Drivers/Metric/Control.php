<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Types = F::Run('IO', 'Execute',
                         [
                             'Execute'   => 'Distinct',
                             'Storage'   => 'Primary',
                             'Scope'     => 'Metric',
                             'Fields'    => ['Type'],
                             'No Where'  => true
                         ]);
        
        $Rows = [];
        foreach ($Types['Type'] as $Type)
            $Rows[] = [
                $Type,
                F::Run('Metric.Get', 'Count', $Call,
                [
                    'Metric' =>
                    [
                        'Type' => $Type
                    ]
                ]),
                F::Run('Metric.Get', 'Sum', $Call,
                [
                    'Metric' =>
                    [
                        'Type' => $Type
                    ]
                ])
            ];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Rows
            ];
        return $Call;
    });
    
    setFn('Menu', function ($Call)
    {
        return [
            'Count' =>
                F::Run('Formats.Number.French', 'Do',
                [
                    'Value' => F::Run('IO', 'Execute',
                        [
                            'Execute'   => 'Count',
                            'Storage'   => 'Primary',
                            'Scope'     => 'Metric',
                            'No Where'  => true
                        ])
                ])
        ];
    });