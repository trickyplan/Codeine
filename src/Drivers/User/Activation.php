<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Send', function ($Call)
    {
        $User = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => $Call['Data']['ID']))[0]; // FIXME

        $User['Code'] = F::Run('Security.UID', 'Get');

        F::Run('IO', 'Write',
            array(
                 'Storage' => 'Activation',
                 'Scope' => 'Activation',
                 'Data' =>
                     array(
                         'ID' => $User['Code'],
                         'User' => $User['ID']
                     )
            ));

        $Message['Scope'] = '"'.F::Dot($User, $Call['Name Field']).'" <'.$User['EMail'].'>';
        $Message['ID']    = $Call['Subject'];

        $Message['Data']  = F::Run('View', 'LoadParsed', $Call,
                                             [
                                                  'Scope' => 'User/Activation',
                                                  'ID' => 'EMail',
                                                  'Data' =>
                                                      array_merge($Call, $User,
                                                      [
                                                          'ActivationURL' => $Call['Host'].'/activate/user/'.$User['Code']
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

        return $Call;
    });

    self::setFn('Check', function ($Call)
    {
        $Activation = F::Run('IO', 'Read',
             array(
                  'Storage' => 'Activation',
                  'Scope' => 'Activation',
                  'Where' => $Call['Code']
             ))[0];

        if ($Activation !== false)
        {
            F::Run('Entity', 'Update',
                array(
                     'Entity' => 'User',
                     'Where' => $Activation['User'],
                     'Data' =>
                         [
                            'Status' => true
                         ]
                ));

            F::Run('IO', 'Write',
                array(
                     'Storage' => 'Activation',
                     'Scope' => 'Activation',
                     'Where' => $Call['Code'],
                     'Data' => null
                ));

            if (isset($Call['Activation']['Auto Login']) && $Call['Activation']['Auto Login'])
                $Call['Session'] = F::Run('Security.Auth', 'Attach', $Call, ['User' => $Activation['User'], 'TTL' => 3600]);

            $Call = F::Hook('Activation.Success', $Call);

        }
        else
            $Call = F::Hook('Activation.Failed', $Call);

        return $Call;
    });