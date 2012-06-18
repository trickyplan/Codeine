<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Identificate', function ($Call)
    {
        $Call['Locales'][] = 'User';

        $Call['Output']['Content'][] = array(
            'Type' => 'Form',
            'Action' => '/auth' //FIXME
        );

        $Call['Output']['Form'][] = array(
            'Type' => 'Form.Textfield',
            'Entity' => 'User',
            'Name' => $Call['Determinant']
        );

        $Call['Output']['Form'][] = array(
            'Type' => 'Form.Password',
            'Mode' => 'One',
            'Entity' => 'User',
            'Name' => 'Password'
        );

        return $Call;
    });

    self::setFn('Authentificate', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read',
                     array(
                          'Entity' => 'User',
                          'Where' =>
                              array(
                                  $Call['Determinant'] => $Call['Request'][$Call['Determinant']],
                                  'Password' => F::Live($Call['Challenger'], array('Value' => $Call['Request']['Password']))
                              )
                     ))[0];

        if (empty($Call['User']))
            $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'ID' => 'Incorrect'
                    );

        return $Call;
    });

    self::setFn('Challenge', function ($Call)
    {
        return F::Live($Call['Challenger'], $Call);
    });