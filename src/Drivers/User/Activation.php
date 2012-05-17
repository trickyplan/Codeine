<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Send', function ($Call)
    {
        $User = $Call['Element'];

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

            $Call['Output']['Content'] = $Call['Widgets']['Success'];
        }
        else
            $Call['Output']['Content'] = $Call['Widgets']['Failure'];



        return $Call;
    });