<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Send', function ($Call)
    {
        $User = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => $Call['Element'] ))[0];
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
                                                  'ID' => 'Activation.EMail',
                                                  'Data' => array_merge($User, array('ActivationURL' => $_SERVER['HTTP_HOST'].'/activate/user/'.$User['Code']))
                                             ));
        $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');

        F::Live($Call['Sender'], $Message);

        list(,$Message['Server']) = explode('@', $Message['Scope']);

        $Call['Output']['Content'] = array(
            array(
                'Type'  => 'Template',
                'Scope' => 'User',
                'Value' => 'Activation/Needed',
                'Data'  => $Message
            )
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
             ));

        if ($Activation)
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
                    'Type'  => 'Template',
                    'Scope' => 'User',
                    'Value' => 'Activation/Success',
                    'Data' => $Activation
                )
        );
        }
        else
            $Call['Output']['Content'] = array(
                array(
                    'Type'  => 'Template',
                    'Scope' => 'User',
                    'Value' => 'Activation/Failure'
                ));

        return $Call;
    });