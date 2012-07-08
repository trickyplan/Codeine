<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Send', function ($Call)
    {
        $User = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => (int) $Call['Data']['ID']))[0]; // FIXME

        $User['Code'] = F::Run('Security.UID.GUID', 'Get');

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

        $Message['Scope'] = $User['EMail'];
        $Message['ID']    = $Call['Subject'];
        $Message['Data']  = F::Run('View', 'LoadParsed',
                                             array(
                                                  'Scope' => 'User',
                                                  'ID' => 'Activation/EMail',
                                                  'Data' => array_merge($User,
                                                      array('ActivationURL' => $_SERVER['HTTP_HOST'].'/activate/user/'.$User['Code']))
                                             ));
        $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');

        F::Live($Call['Sender'], $Message);

        list(,$User['Server']) = explode('@', $User['EMail']);

        $Call['Output']['Content'] = array(
            array(
                'Type'  => 'Template',
                'Scope' => 'User',
                'ID' => 'Activation/Needed',
                'Data'  => $User
            )
        );

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
            F::Run('Entity', 'Set',
                array(
                     'Entity' => 'User',
                     'Where' => $Activation['User'],
                     'Data' => array(
                                    'Status' => 1
                                )
                ));

            F::Run('Security.Auth', 'Attach', array('User' => $Activation['User']));

            F::Run('IO', 'Write',
                array(
                     'Storage' => 'Activation',
                     'Scope' => 'Activation',
                     'Where' => $Call['Code'],
                     'Data' => null
                ));

            $Call['Output']['Content'] = array(
                array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Activation/Success',
                    'Data' => $Activation
                )
            );
        }
        else
            $Call['Output']['Content'] = array(
                array(
                    'Type'  => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Activation/Failure'
                ));

        return $Call;
    });