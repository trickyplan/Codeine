<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeReset', $Call);
        return F::Run(null, $Call['HTTP Method'], $Call);
    });

    setFn('ByID', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call,  ['Entity' => 'User', 'One' => true]);

        if (!empty($Call['User']))
        {
            $NewPassword = F::Live($Call['Password']['Generator']);

            F::Run('Entity', 'Update',
                [
                      'Entity' => 'User',
                      'Purpose' => 'Reset',
                      'Where'  => $Call['User']['ID'],
                      'Data' =>
                       [
                            [
                                'Password' => $NewPassword
                            ]
                       ]
                ]);

            $Message['Scope'] = $Call['User']['EMail'];
            $Message['ID']    = 'Восстановление пароля';
            $Message['Data']  = F::Run('View', 'Load',
                                                 [
                                                      'Scope' => 'User',
                                                      'ID' => 'Reset/EMail',
                                                      'Data' =>
                                                        array_merge($Call['User'],
                                                            [
                                                                'Password' => $NewPassword,
                                                                'Host' => $Call['Host']
                                                            ])
                                                 ]);

            $Message['Headers'] = ['Content-type:' => ' text/html; charset="utf-8"'];

            F::Live($Call['Sender'], $Message);

            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Reset/Success'
            ];

            F::Log('User reset password '.$Call['User']['ID'], LOG_INFO, 'Security');
        }
        else
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Reset/404'
                ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        return F::Run(null, 'ByID', $Call,
            [
                'Where' =>
                [
                    'EMail' => $Call['Request']['EMail']
                ]
            ]);
    });

    setFn('GET', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Reset/Form'
            ];

        return $Call;
    });