<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Activation email 
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeReset', $Call);
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('ByID', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call, ['Entity' => 'User', 'One' => true]);

        if (empty($Call['User']))
        {
                $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Reset/404'
                ];
        }
        else
        {
            $Call['User']['Password'] = F::Live($Call['Reset']['Generator']);
            $Password = $Call['User']['Password'];
            
            F::Run('Entity', 'Update',
                [
                    'Entity'  => 'User',
                    'Purpose' => 'Reset',
                    'One'     => true,
                    'Where'   => $Call['User']['ID'],
                    'Data'    => $Call['User']
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
                    'Scope' => 'User/Reset',
                    'ID'    => 'EMail',
                    'Data'  => F::Merge($Call['User'], ['Password' => $Password])
                ];

            $VCall = F::Run('View', 'Render', $VCall, ['Context' => 'mail']);

            F::Run('IO', 'Write', $VCall,
                [
                    'Storage'   => $Call['Reset']['Send To'],
                    'Where'     => F::Run('Locale', 'Get', $Call, ['Message' => 'User.Reset:PasswordRecovery']),
                    'Scope'     => $Call['User']['EMail'],
                    'Data'      => $VCall['Output']
                ]
            );

            F::Log('User reset password '.$Call['User']['ID'], LOG_INFO, 'Security');

            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Reset/Success'
            ];
        }

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