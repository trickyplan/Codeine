<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Unified Control 
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Time = time();

        $Totals = [];
        $Totals['Total'] = F::Run('Entity', 'Count', ['Entity' => $Call['Bundle'], 'No Where' => true]);
        $Totals['Today'] = F::Run('Entity', 'Count',
                        [
                            'Entity' => $Call['Bundle'],
                            'Where'  =>
                            [
                                'Created' =>
                                [
                                    '$lt' => $Time,
                                    '$gt' => $Time-86400
                                ]
                            ]
                        ]);
        $Totals['Hour'] = F::Run('Entity', 'Count',
                            [
                                'Entity' => $Call['Bundle'],
                                'Where'  =>
                                    [
                                        'Created' =>
                                            [
                                                '$lt' => $Time,
                                                '$gt' => $Time-3600
                                            ]
                                    ]
                            ]);
        $Totals['Minute'] = F::Run('Entity', 'Count',
                            [
                                'Entity' => $Call['Bundle'],
                                'Where'  =>
                                    [
                                        'Created' =>
                                            [
                                                '$lt' => $Time,
                                                '$gt' => $Time-60
                                            ]
                                    ]
                            ]);

        $Totals['Second'] = F::Run('Entity', 'Count',
                            [
                                'Entity' => $Call['Bundle'],
                                'Where'  =>
                                    [
                                        'Created' =>
                                            [
                                                '$eq' => $Time
                                            ]
                                    ]
                            ]);

        $Table = [
                    'Total' =>
                    [
                        '<l>'.$Call['Bundle'].'.Control:Total</l>',
                        $Totals['Total']
                    ]
                ];

        foreach ($Totals as $Key => $Total)
            //if ($Total > 0)
                $Table[$Key] =
                    [
                        '<l>'.$Call['Bundle'].'.Control:'.$Key.'</l>',
                        '<number>'.$Totals[$Key].'</number>'
                    ];


        $Call['Output']['Content'][] =
            [
                'Type'  => 'Table',
                'Value' => $Table
            ];

        return $Call;
    });

    setFn('Set', function ($Call)
    {
        $Call['Object'] = F::Run('Entity', 'Update', $Call,
        [
            'Entity' => $Call['Bundle'],
            'Data' => $Call['Request'],
            'One'  => true
        ]);

        $Call['Scope'] = isset($Call['Scope'])? $Call['Bundle'].'/'.$Call['Scope'] : $Call['Bundle'];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => $Call['Scope'],
                'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                'Data' => $Call['Object']
            ];

        $Call = F::Run('System.Interface.HTTP', 'RestoreURL', $Call);

        return $Call;
    });

    setFn('List', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'List',
            'Context' => ''
        ];

        return F::Apply('Entity.List', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Show', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Show.Static'
        ];
        $Call = F::Apply('Entity.Show.Static', 'Before', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);

        return F::Apply('Entity.Show.Static', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Create', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Create'
        ];

        return F::Apply('Entity.Create', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Update', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Update'
        ];

        $Call = F::Apply('Entity.Update', 'Before', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
        $Call = F::Apply('Entity.Update', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);

        return $Call;
    });

    setFn('Verify', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Verify'
        ];

        return F::Apply('Entity.Verify', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Delete', function ($Call)
    {
        if (isset($Call['Request']['Where']))
            $Call['Where'] = $Call['Request']['Where'];

        $Call['No Where'] = true;

        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Delete'];
        $Call = F::Apply('Entity.Delete', 'Before', $Call, ['Entity' => $Call['Bundle'],
            'Scope' => 'Control']);
        
        return F::Apply('Entity.Delete', 'Do', $Call, ['Entity' => $Call['Bundle'],
            'Scope' => 'Control']);
    });

    setFn('Truncate', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Truncate'];
        return F::Apply('Entity.Truncate', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Menu', function ($Call)
    {
        return [
            'Count' =>
                F::Run('Formats.Number.French', 'Do',
                [
                    'Value' => F::Run('Entity', 'Count',
                        [
                            'Entity' => $Call['Bundle'],
                            'Scope' => 'Control',
                            'No Where' => true
                        ])
                ])
        ];
    });

    setFn('Export', function ($Call)
    {
        return F::Apply('Entity.Export', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Search', function ($Call)
    {
        return F::Apply('Entity.Search', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Allow', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Allow'];
        return F::Apply('Entity.Allow', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Disallow', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Disallow'];
        return F::Apply('Entity.Disallow', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Touch', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Touch'];
        return F::Apply('Entity.Touch', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Import', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Import'];
        return F::Apply('Entity.Import', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Populate', function ($Call)
    {
        return F::Apply('Entity.Populator', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Renumerate', function ($Call)
    {
        return F::Apply('Entity.Renumerate', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Search', function ($Call)
    {
        return F::Apply('Entity.Search', 'Do', $Call, ['Provider' => $Call['Bundle'], 'Query' => $Call['Where']]);
    });