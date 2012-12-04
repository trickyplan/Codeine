<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $IDs = F::Run('Search', 'Query', $Call,
             array(
                  'Engine' => 'Primary',
                  'Entity' => $Call['Entity'],
                  'Query' => isset($Call['Query'])? $Call['Query']: $Call['Request']['Query']
             ));

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main',
                    'Context' => $Call['Context']
                );

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Search',
                    'Context' => $Call['Context']
                );

        $Call['Locales'][] = $Call['Entity'];

        $Where = [];

        if (!empty($IDs) && null !== $IDs)
        {
            $Where['ID'] = [
                        'IN' => array_keys($IDs)
                    ];
        }

        if (isset($Call['Request']['Filter']))
            foreach ($Call['Request']['Filter'] as $Key => $Value)
                $Where[$Key] = $Value;

        $Call = F::Run('Entity.List', 'Do',
                $Call,
                [
                'Context' => 'app',
                'Where' => $Where,
                'Template' => (
                isset($Call['Template'])?
                    $Call['Template']:
                    'Search')
                ]
            );

        return $Call;

     });