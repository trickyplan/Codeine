<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Context']))
            $Call['Context'] = '';

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        return $Call;
    });

    setFn('POST', function ($Call)
    {
        if (!isset($Call['Entity']))
            $Entities = $Call['Entities'];
        else
            $Entities = [$Call['Entity']];

        foreach ($Entities as $Entity)
        {
            $Call['Where'] = [];

            $Call['Entity'] = $Entity;

            $IDs = F::Run('Search', 'Query', $Call,
            [
                  'Engine' => 'Primary',
                  'Query' => isset($Call['Query'])? $Call['Query']: $Call['Request']['Query']
            ]);

            $Call['Output']['Content'][] =
                    [
                        'Type' => 'Template',
                        'Scope' => $Entity,
                        'ID' => 'Search',
                        'Context' => ''
                    ];

            if (!empty($IDs))
            {
                $Where['ID'] = ['$in' => array_keys($IDs)];

                $Call = F::Run('Entity.List', 'Do',
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
            }
            else
                $Call['Output']['Content'][] =
                    [
                        'Type' => 'Template',
                        'Scope' => $Entity,
                        'ID' => 'NotFound',
                        'Context' => ''
                    ];

            unset($Call['Scope'], $Call['Elements'], $Call['Context']);
        }
        return $Call;

     });