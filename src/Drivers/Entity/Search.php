<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

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
            $Call['Highlight'] = $Call['Query'];

            foreach ($Entities as $Entity)
            {
                $Call['Where'] = [];

                $Call['Entity'] = $Entity;

                $IDs = F::Run('Search', 'Query', $Call,
                [
                      'Engine' => 'Primary'
                ]);

                $Call['Output']['Content'][] =
                        [
                            'Type' => 'Template',
                            'Scope' => $Entity,
                            'ID' => 'Search',
                            'Context' => $Call['Context']
                        ];

                if (!empty($IDs))
                {
                    $Where['ID'] = ['$in' => array_keys($IDs)];

                    $VCall = F::Apply('Entity.List', 'Do',
                        $Call,
                        [
                            'Context' => 'app',
                            'Where!' => $Where,
                            'Template' => (
                            isset($Call['Template'])?
                                $Call['Template']:
                                'Short')
                        ]
                    );

                    $Call['Output'] = F::Merge($Call['Output'], $VCall['Output']);
                }
                else
                    $Call['Output']['Content'][] =
                        [
                            'Type' => 'Template',
                            'Scope' => $Entity,
                            'ID' => 'NotFound',
                            'Context' => $Call['Context']
                        ];

                unset($Call['Scope'], $Call['Elements']);
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