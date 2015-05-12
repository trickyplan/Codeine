<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Index', function ($Call)
    {
        // if (isset($Call['Providers'][$Call['Entity']]))
        {
            $Data = [];

            if (isset($Call['Data']))
                ;
            else
                $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true]);

            foreach ($Call['Nodes'] as $Name => $Node)
                if (isset($Node['Index']) && $Node['Index'])
                {
                    $Value = F::Dot($Call['Data'], $Name);

                    if (empty($Value))
                        ;
                    else
                    {
                        if (is_array($Value))
                            $Data[$Name] = implode (' ', $Value);
                        else
                            $Data[$Name] = $Value;
                    }
                }

            if (F::Run('Search', 'Index', $Call,
            [
                'Provider' => $Call['Entity'],
                'Data!'    => $Data
            ]))
            {
                F::Log($Call['Entity'].' '.$Data['ID'].' indexed', LOG_INFO);
                F::Log($Data, LOG_DEBUG);
            }
        }
/*        else
            F::Log('Cannot find provider for '.$Call['Entity'], LOG_ERR);*/
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Entity']))
            $Entities = $Call['Entities'];
        else
            $Entities = [$Call['Entity']];

        if (isset($Call['Request']['Query']) && !isset($Call['Query']))
            $Call['Query'] = $Call['Request']['Query'];

        if (isset($Call['Query']) && !empty($Call['Query']))
        {
            $Call['View']['Highlight'] = $Call['Query'];

            foreach ($Entities as $Entity)
            {
                $Call['Where'] = [];

                $Call['Entity'] = $Entity;

                $Call = F::Run('Search', 'Query', $Call,
                [
                      'Provider' => $Call['Entity']
                ]);

                $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => $Entity,
                    'ID' => 'Search'
                ];

            }
        }

        return $Call;
     });

    setFn('Suggestions', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'application/x-suggestions+json';
        $Suggestions = [];

        if (!isset($Call['Entity']))
            $Entities = $Call['Entities'];
        else
            $Entities = [$Call['Entity']];

        foreach ($Entities as $Entity)
        {
            $IDs = F::Run('Search', 'Query', $Call,
            [
                'Entity' => $Entity,
                'Engine' => 'Primary',
                'Query'  => $Call['Request']['Query']
            ]);

            if (null === $IDs)
                ;
            else
            {
                $Results = F::Run('Entity', 'Read',
                [
                    'Entity' => $Entity,
                    'Fields' => ['Title'],
                    'Where' =>
                    [
                        'ID' =>
                        [
                            '$in' => array_keys($IDs)
                        ]
                    ]
                ]);

                foreach ($Results as $Result)
                {
                    $Suggestions[] = $Result['Title'];
                }
            }
        }

        $Call['Output']['Content'] =
        [
            $Call['Request']['Query'],
            $Suggestions
        ];

        return $Call;
    });

    setFn('Reindex.All', function ($Call)
    {
        $Call   = F::Apply('Entity', 'Load', $Call);
        $Max  = F::Run('Entity', 'Read', $Call,
            [
                'One'  => true,
                'Limit' =>
                [
                    'From' => 0,
                    'To'   => 1
                ],
                'Sort' =>
                [
                    'ID' => false
                ]
            ])['ID'];

        $Amount = ceil($Max / $Call['Reindex']['Objects Per Page']);

        F::Log('Total objects: '.$Max, LOG_INFO);
        F::Log('Limit per page: '.$Call['Reindex']['Objects Per Page'], LOG_INFO);
        F::Log('Pages: '.$Amount, LOG_INFO);

        F::Run('Code.Run.Parallel', 'Run', $Call,
            [
                'Run' =>
                [
                    'Service' => 'Entity.Search',
                    'Method'  => 'Reindex.Page'
                ],
                'Data'  => range(0, $Amount),
                'Key'   => 'Page'
            ]);

        return ['Indexed' => $Amount];
    });

    setFn('Reindex.Page', function ($Call)
    {
        foreach ($Call['Page'] as $Page)
        {
            $Objects = F::Run('Entity', 'Read', $Call,
                [
                    'No Memo' => true,
                    'One' => false,
                    'Sort' =>
                    [
                        'ID' => true
                    ],
                    'Where' =>
                    [
                        'ID' =>
                        [
                            '$gt' => $Page*$Call['Reindex']['Objects Per Page']
                        ]
                    ],
                    'Limit' =>
                    [
                        'From' => 0,
                        'To'   => $Call['Reindex']['Objects Per Page']
                    ]
                ]);

            if (empty($Objects))
                ;
            else
                foreach($Objects as $Data)
                    F::Run(null, 'Index', $Call, ['Data!' => $Data]);

            F::Log('Reindex Page № '.$Page, LOG_WARNING);
        }
        return $Call;
    });

    setFn('Remove', function ($Call)
    {
        if (isset($Call['Data']))
            ;
        else
            $Call['Data'] = F::Run('Entity', 'Read', $Call);

        if (F::Run('Search', 'Remove', $Call,
        [
            'Provider' => $Call['Entity'],
            'Data!'    => ['ID' => $Call['Data']['ID']]
        ]))
        {
            F::Log($Call['Entity'].' '.$Call['Data']['ID'].' removed', LOG_INFO);
            F::Log($Call['Data'], LOG_DEBUG);
        }

        return $Call;
    });

