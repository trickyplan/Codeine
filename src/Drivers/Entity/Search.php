<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        // TODO Realize "Do" function
        $IDs = F::Run('Search', 'Query',
             array(
                  'Engine' => 'Primary',
                  'Query' => $Call['Request']['Query']
             ));

        //d(__FILE__, __LINE__, $Call['Request']['Query']);
        //d(__FILE__, __LINE__, $IDs);
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
                'Value' => 'Empty'
            );
        else
        {
            foreach ($Elements as $Element)
                $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Short'),
                    'Data' => $Element
                );
        }
        return $Call;
     });