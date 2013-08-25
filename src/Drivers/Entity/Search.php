<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
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
            $Call['Entity'] = $Entity;

            $IDs = F::Run('Search', 'Query', $Call,
            [
                  'Engine' => 'Primary',
                  'Query' => isset($Call['Query'])? $Call['Query']: $Call['Request']['Query']
            ]);

            $Call['Layouts'][] = ['Scope' => $Entity,'ID' => 'Search','Context' => $Call['Context']];

            if (!empty($IDs) && null !== $IDs)
            {
                $Where['ID'] = [];
                $Where['ID']['$in'] = array_keys($IDs);

                $Call = F::Run('Entity.List', 'Do',
                    $Call,
                    [
                        'Where!' => $Where,
                        'Template' => (
                        isset($Call['Template'])?
                            $Call['Template']:
                            'Short')
                    ]
                );
            }
            else
                $Call['Layouts'][] = ['Scope' => $Entity,'ID' => 'Empty','Context' => $Call['Context']];

        }

        return $Call;

     });