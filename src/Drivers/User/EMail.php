<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Activation email 
     * @package Codeine
     * @version 8.x
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
                         'ID'      => (int) $Call['Data']['Code'],
                         'User'    => $Call['Data']['ID'],
                         'BackURL' => isset($Call['Request']['BackURL'])? $Call['Request']['BackURL']: '/'
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
                'ID' => $Call['EMail Activation']['Subject'],
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
        $Call['ActivationData'] = F::Run('IO', 'Read',
             [
                  'Storage' => 'Primary',
                  'Scope' => 'Activation',
                  'Where' => (int) $Call['Code']
             ])[0];

        if ($Call['ActivationData'] !== null)
        {
            F::Run('Entity', 'Update', $Call,
                [
                     'Entity' => 'User',
                     'Where' => $Call['ActivationData']['User'],
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

            if (isset($Call['EMail Activation']['Auto Login']) && $Call['EMail Activation']['Auto Login'])
                $Call = F::Apply('Session', 'Write', $Call, ['Session Data' => ['User' => $Call['ActivationData']['User']]]);

            $Call = F::Hook('Activation.Success', $Call);
        }
        else
            $Call = F::Hook('Activation.Failed', $Call);

        return $Call;
    });