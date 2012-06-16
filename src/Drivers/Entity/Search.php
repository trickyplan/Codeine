<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $IDs = F::Run('Search', 'Query',
             array(
                  'Engine' => 'Primary',
                  'Entity' => $Call['Entity'],
                  'Query' => $Call['Request']['Query']
             ));

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main'
                );

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Search'
                );

        //d(__FILE__, __LINE__, $Call['Request']['Query']);
        // d(__FILE__, __LINE__, $IDs);
        if ($IDs !== null)
            $Elements = F::Run('Entity', 'Read', array(
                              'Entity' => $Call['Entity'],
                              'Where' => array('ID' => array('IN' => array_keys($IDs)))
                         ));
        else
            $Elements = array();

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'ID' => 'EmptySearch'
            );
        else
        {
            foreach ($Elements as $Element)
                $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Search'),
                    'Data' => $Element
                );
        }
        return $Call;
     });