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

        $Call['Locales'][] = $Call['Entity'];
        $Call['Output']['Content'][] = '<h2 class="page-header"><l>'.$Call['Entity'].'.Entity</l></h2>';

        if (!empty($IDs) && null !== $IDs)
            $Call = F::Run('Entity.List', 'Do', $Call,
                array(
                    'Where' => array('ID' => array('IN' => array_keys($IDs))),
                    'Template' => (isset($Call['Template'])? $Call['Template']: 'Search')));
        else
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => $Call['Entity'],
                    'ID' => 'EmptySearch'
                );

        return $Call;

     });