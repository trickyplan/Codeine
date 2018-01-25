<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type'  => 'Template',
                'Scope' => 'Security.Auth',
                'ID'    => 'Password'
            ];
        
        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        if (isset($Call['Request'][$Call['Determinant']]))
        {
            if (isset($Call['Request']['Password']))
            {
                $Call['User'] =
                    F::Run('Entity', 'Read',
                        [
                            'Entity' => 'User',
                            'Where' =>
                            [
                                $Call['Determinant'] => $Call['Request'][$Call['Determinant']]
                            ],
                            'One' => true
                        ]);
                        
                    $Challenge = F::Run('Security.Hash', 'Get', $Call,
                         [
                              'Mode' => 'Password',
                              'Value' => $Call['Request']['Password'],
                              'Salt' => isset($Call['User']['Salt'])? $Call['User']['Salt']: ''
                         ]);
                         
                    if ($Call['User']['Password'] != $Challenge)
                    {
                        F::Log('Passwords don\'t match', LOG_WARNING, 'Security');
                        F::Log('User password hash is '.$Call['User']['Password'], LOG_WARNING, 'Security');
                        F::Log('Request password hash is '.$Challenge, LOG_WARNING, 'Security');
                        
                        $Call['Output']['Content'][] =
                            [
                                'Type' => 'Template',
                                'Scope' => 'User/Authenticate',
                                'ID' => 'Incorrect'
                            ];
                            
                        unset($Call['User']);
                    }
                    else
                        F::Log('Passwords match', LOG_NOTICE, 'Security');
            }
            else
                F::Log('Password isn\'t set', LOG_WARNING, 'Security');
        }
            else
                F::Log('User isn\'t set', LOG_WARNING, 'Security');

        return $Call;
    });
