<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    setFn('Verify', function ($Call)
    {
        if (isset($Call['Data']['EMail']))
        {
            $Call['Data']['Code'] = F::Run('Security.UID', 'Get');

            F::Run('IO', 'Write',
                [
                     'Storage' => 'Primary',
                     'Scope' => 'Activation',
                     'Data' =>
                     [
                         'ID' => (int) $Call['Data']['Code'],
                         'User' => $Call['Data']['ID']
                     ]
                ]);

            $VCall = $Call;

            $VCall['Layouts'] =
                [[
                    'Scope' => 'Project',
                    'ID'    => 'Zone',
                    'Context' => 'mail'
                ]];

            $VCall['Output']['Content'] =
                [[
                    'Type'  => 'Template',
                    'Scope' => 'User/Activation',
                    'ID'    => 'EMail',
                    'Data'  => $Call['Data']
                ]];

            $VCall = F::Run('View', 'Render', $VCall, ['Context' => 'mail']);

            F::Run('IO', 'Write', $VCall, [
                'Storage' => 'EMail',
                'ID' => 'Активация аккаунта',
                'Scope' => $Call['Data']['EMail'],
                'Data' => $VCall['Output']]
            );

            return $Call['Data']['EMail'];
        }
        else
            return null;
    });

    setFn('Check', function ($Call)
    {
        $Activation = F::Run('IO', 'Read',
             [
                  'Storage' => 'Primary',
                  'Scope' => 'Activation',
                  'Where' => (int) $Call['Code']
             ])[0];

        if ($Activation !== null)
        {
            F::Run('Entity', 'Update', $Call,
                [
                     'Entity' => 'User',
                     'Where' => $Activation['User'],
                     'Data' =>
                     [
                        'Status' => 1
                     ]
                ]);

            F::Run('IO', 'Write',
                [
                     'Storage' => 'Primary',
                     'Scope' => 'Activation',
                     'Where' => $Call['Code'],
                     'Data' => null
                ]);

            if (isset($Call['Activation']['Auto Login']) && $Call['Activation']['Auto Login'])
                $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['User' => $Activation['User']]]);

            $Call = F::Hook('Activation.Success', $Call);
        }
        else
            $Call = F::Hook('Activation.Failed', $Call);

        return $Call;
    });