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
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('ByID', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call,  ['Entity' => 'User', 'One' => true]);

        if (!empty($Call['User']))
        {
            $NewPassword = F::Live($Call['Password']['Generator']);

            $Call['User'] = F::Run('Entity', 'Update',
                [
                      'Entity' => 'User',
                      'Purpose' => 'Reset',
                      'One' => true,
                      'Where'  => $Call['User']['ID'],
                      'Data' =>
                       [
                            [
                                'Password' => $NewPassword
                            ]
                       ]
                ]);

            $VCall = $Call;

            $VCall['Layouts'] =
                [[
                    'Scope' => 'Project',
                    'ID'    => 'Zone',
                    'Context' => 'mail'
                ]];

            $VCall['Output']['Content'][] =
                [
                    'Type'  => 'Template',
                    'Scope' => 'User',
                    'ID'    => 'Reset/EMail',
                    'Data'  => F::Merge($Call['User'], ['Password' => $NewPassword])
                ];

            $VCall = F::Run('View', 'Render', $VCall, ['Context' => 'mail']);

            F::Run('IO', 'Write', $VCall, [
                'Storage' => 'EMail',
                'ID' => 'Восстановление пароля',
                'Scope' => $Call['User']['EMail'],
                'Data' => $VCall['Output']]
            );

            F::Log('User reset password '.$Call['User']['ID'], LOG_INFO, 'Security');

            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Reset/Success'
            ];
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