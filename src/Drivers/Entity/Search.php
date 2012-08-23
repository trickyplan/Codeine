<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $IDs = F::Run('Search', 'Query',
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

        if (!empty($IDs) && null !== $IDs)
            $Call = F::Run('Entity.List', 'Do',
                    $Call,
                    [
                        'Where' =>
                            [
                                'ID' =>
                                    [
                                        'IN' => array_keys($IDs)
                                    ]
                            ],
                        'Template' => (
                                isset($Call['Template'])?
                                $Call['Template']:
                                'Search')
                    ]
                );
        else
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => $Call['Entity'],
                    'ID' => 'EmptySearch'
                );

        return $Call;

     });