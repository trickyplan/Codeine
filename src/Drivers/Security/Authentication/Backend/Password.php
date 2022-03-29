<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 43.6.1
     */

    setFn('Do', function ($Call) {
        $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'Security/Authentication/Backend',
                'ID' => 'Password'
            ];

        return $Call;
    });

    setFn('Identificate', function ($Call) {
        // Just show us the password form
        return $Call;
    });

    setFn('Authenticate', function ($Call) {
        if (F::Dot($Call, 'Request.' . $Call['Determinant'])) {
            if (F::Dot($Call, 'Request.Password')) {
                $Call['Candidate'] =
                    F::Run(
                        'Entity',
                        'Read',
                        $Call,
                        [
                            'Entity' => 'User',
                            'Where' =>
                                [
                                    $Call['Determinant'] => $Call['Request'][$Call['Determinant']]
                                ],
                            'One' => true
                        ]
                    );

                if (empty($Call['Candidate'])) {
                    $Call['Errors'][] = 'Password.Incorrect';
                } else {
                    $Challenge = F::Run(
                        'Security.Hash',
                        'Get',
                        $Call,
                        [
                            'Security' =>
                                [
                                    'Hash' =>
                                        [
                                            'Mode' => 'Password' // FIXME Salt
                                        ]
                                ],
                            'Value' => F::Dot($Call, 'Request.Password')
                        ]
                    );

                    $Password = F::Dot($Call, 'Candidate.Password');

                    if ($Password != $Challenge) {
                        $Call['Errors'][] = 'Password.Incorrect';
                        F::Log('Passwords don\'t match', LOG_WARNING, 'Security');
                        F::Log('User password hash is ' . $Password, LOG_WARNING, 'Security');
                        F::Log('Request password hash is ' . $Challenge, LOG_WARNING, 'Security');
                    } else {
                        F::Log('Passwords match', LOG_NOTICE, 'Security');
                    }
                }
            } else {
                F::Log('Password isn\'t set', LOG_WARNING, 'Security');
                $Call['Errors'][] = 'Undefined.Password';
            }
        } else {
            F::Log('User isn\'t set', LOG_WARNING, 'Security');
            $Call['Errors'][] = 'Undefined.User';
        }

        return $Call;
    });