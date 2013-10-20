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

                    $Call = F::Apply('Entity.List', 'Do',
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