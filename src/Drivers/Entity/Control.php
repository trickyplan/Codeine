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

        $Call['Output']['Content'][]
        =
            [
                'Type'  => 'Table',
                'Value' =>
                [
                    [
                    '<l>'.$Call['Bundle'].'.Control:Total</l>',
                    F::Run('Entity', 'Count', ['Entity' => $Call['Bundle']])
                    ],
                    [
                    '<l>'.$Call['Bundle'].'.Control:Today</l>',
                    F::Run('Entity', 'Count',
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
                        ])
                    ],
                    [
                        '<l>'.$Call['Bundle'].'.Control:Hour</l>',
                        F::Run('Entity', 'Count',
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
                            ])
                    ],
                    [
                        '<l>'.$Call['Bundle'].'.Control:Minute</l>',
                        F::Run('Entity', 'Count',
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
                            ])
                    ],
                    [
                        '<l>'.$Call['Bundle'].'.Control:Second</l>',
                        F::Run('Entity', 'Count',
                            [
                                'Entity' => $Call['Bundle'],
                                'Where'  =>
                                    [
                                        'Created' =>
                                            [
                                                '$eq' => $Time
                                            ]
                                    ]
                            ])
                    ]
                ]
            ];
        return $Call;
    });

    setFn('Set', function ($Call)
    {
        F::Run('Entity', 'Update', $Call,
        [
            'Entity' => $Call['Bundle'],
            'Data' => $Call['Request']
        ]);

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

        return F::Apply('Entity.List', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control', 'Show Redirects' => true]);
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
                            'Scope' => 'Control'
                        ])
                ])
        ];
    });

    setFn('Export', function ($Call)
    {
        if (isset($Call['Request']['Fields']))
            $Call['Fields'] = $Call['Request']['Fields'];

        $Elements = F::Run('Entity', 'Read', $Call,
                    [
                         'Entity' => $Call['Bundle']
                    ]);

        $Call['View']['Renderer'] =
            [
                'Service' =>  'View.JSON',
                'Method' =>  'Render'
            ];

        foreach ($Elements as $Element)
        $Call['Output']['Content'][] =
        [
            'Type'  => 'Template',
            'Scope' => $Call['Bundle'],
            'ID'    => 'Export',
            'Data'  => $Element
        ];

        return $Call;
    });

    setFn('Export.CSV', function ($Call)
    {
        $Call = F::loadOptions($Call['Bundle'].'.Entity', null, $Call);

        $Call['Fields'] = [];

        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['CSV']) && $Node['CSV'])
                $Call['Fields'][] = $Name;

        $ElementsCount = F::Run('Entity', 'Count', $Call,
            [
                'Entity' => $Call['Bundle']
            ]);
        $PageCount = $ElementsCount / $Call['EPP'];

        for ($Call['Page'] = 0; $Call['Page'] < $PageCount; $Call['Page']++)
        {
            $Elements = F::Run('Entity', 'Read', $Call,
                [
                    'Entity'  => $Call['Bundle'],
                    'No Memo' => true,
                    'Limit'   =>
                        [
                            'From' => 0,
                            'To'   => $Call['EPP']
                        ],
                    'Where'  =>
                    [
                        'ID' =>
                        [
                            '$gt' => $Call['Page']*$Call['EPP']
                        ]
                    ],
                    'Sort' => ['ID' => true]
                ]);

            foreach ($Elements as $Element)
                $Call['Output']['Content'][] =
                    $Element;
        }

        $Call['View']['Renderer'] =
            [
                'Service' =>  'View.CSV',
                'Method' =>  'Render'
            ];

        return $Call;
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

    setFn('Dict', function ($Call)
    {
        $Rows = F::Run ('Entity', 'Read',
        [
            'Entity' => $Call['Bundle'],
            'Fields' => ['Location']
        ]);

        if (!empty($Rows))
        {
            foreach($Rows as &$Row)
                if (isset($Row['Location']))
                    $Call['Output']['Content'][] = $Row['Location'];

            $Call['Output']['Content'] = array_unique($Call['Output']['Content']);
        }

        return $Call;
    });