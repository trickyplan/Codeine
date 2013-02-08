<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Identificate', function ($Call)
    {
        $Call['Locales'][] = 'User';

        $Call['Output']['Content'][] = array(
            'Type' => 'Form',
            'Action' => '/auth' //FIXME
        );
        $Call['Output']['Form'][] = array(
            'Type' => 'Form.Textfield',
            'Entity' => 'User',
            'Name' => $Call['Determinant'],
            'Value' => isset($_COOKIE['Determinant'])? $_COOKIE['Determinant']: ''
        );

        $Call['Output']['Form'][] = array(
            'Type' => 'Form.Password',
            'Mode' => 'One',
            'Entity' => 'User',
            'Name' => 'Password'
        );

        $Call['Output']['Form'][] = array(
            'Type' => 'Form.Checkbox',
            'Entity' => 'User',
            'Name' => 'TTL',
            'Value' => 'Long'
        );

        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read',
                     array(
                          'Entity' => 'User',
                          'Where' =>
                              [
                                  $Call['Determinant'] => $Call['Request'][$Call['Determinant']]
                              ]
                     ))[0];

        $Challenge = F::Run('Security.Hash', 'Get',
                                     [
                                          'Mode' => 'Password',
                                          'Value' => $Call['Request']['Password'],
                                          'Salt' => isset($Call['User']['Salt'])? $Call['User']['Salt']: ''
                                     ]);


        if ($Call['User']['Password'] != $Challenge)
        {
            $Call['Output']['Content'][]
                = array(
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Incorrect'
            );

            unset($Call['User']);
        }

        return $Call;
    });