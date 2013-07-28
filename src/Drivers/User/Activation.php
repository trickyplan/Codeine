<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    setFn('Send', function ($Call)
    {
        $Call['Data'] = $Call['Data'][0];
        if (isset($Call['Data']['ID']))
            {
                $User = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => $Call['Data']['ID'], 'One' => true)); // FIXME

                $User['Code'] = F::Run('Security.UID', 'Get');

                F::Run('IO', 'Write',
                    array(
                         'Storage' => 'Primary',
                         'Scope' => 'Activation',
                         'Data' =>
                             [[
                                 'ID' => (int) $User['Code'],
                                 'User' => $User['ID']
                             ]]
                    ));

                $Message['Scope'] = '"'.F::Dot($User, $Call['Name Field']).'" <'.$User['EMail'].'>';
                $Message['ID']    = $Call['Subject'];

                $Message['Data']  = F::Run('View', 'Load', $Call,
                                                     [
                                                          'Scope' => 'User/Activation',
                                                          'ID' => 'EMail',
                                                          'Data' =>
                                                              array_merge($Call, $User,
                                                              [
                                                                  'ActivationURL' => $Call['Host'].'/activate/user/'.$User['Code'],
                                                                  'Host' => $Call['Host']
                                                              ])
                                                     ]);

                $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');

                F::Run('Code.Run.Delayed', 'Run', [
                    'Run' => F::Merge($Call['Sender'], ['Call' => $Message])
                ]);

                list(,$User['Server']) = explode('@', $User['EMail']);

                $Call['Output']['Content'] =
                [
                    [
                        'Type'  => 'Template',
                        'Scope' => 'User/Activation',
                        'ID' => 'Needed',
                        'Data'  => $User
                    ]
                ];

                if (isset($Call['Second']))
                    $Call['Output']['Message'][] =
                        array(
                            'Type'  => 'Block',
                            'Class' => 'alert alert-success',
                            'Value'  => 'Письмо выслано повторно'
                        );
            }

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        $Activation = F::Run('IO', 'Read',
             array(
                  'Storage' => 'Primary',
                  'Scope' => 'Activation',
                  'Where' => (int) $Call['Code']
             ))[0];

        if ($Activation !== null)
        {
            F::Run('Entity', 'Update', $Call,
                [
                     'Entity' => 'User',
                     'Where' => $Activation['User'],
                     'One' => true,
                     'Data' =>
                         [
                            'Status' => true
                         ]
                ]);

            F::Run('IO', 'Write',
                array(
                     'Storage' => 'Primary',
                     'Scope' => 'Activation',
                     'Where' => $Call['Code'],
                     'Data' => null
                ));

            if (isset($Call['Activation']['Auto Login']) && $Call['Activation']['Auto Login'])
                $Call['Session'] = F::Run('Session', 'Write', $Call, ['Data' => ['User' => $Activation['User']]]);

            $Call = F::Hook('Activation.Success', $Call);

        }
        else
            $Call = F::Hook('Activation.Failed', $Call);

        return $Call;
    });