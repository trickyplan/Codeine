<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Add', function ($Call)
    {
        $Data = [];

        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Index']) && $Node['Index'])
                $Data[$Name] = F::Dot($Call['Data'], $Name);

        F::Run('Search', 'Add', $Call,
        [
            'Provider' => $Call['Entity'],
            'Data!'     => $Data
        ]);

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
                            'ID' => 'Search',
                            'Context' => $Call['Context']
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