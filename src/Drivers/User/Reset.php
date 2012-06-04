<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('POST', function ($Call)
    {
        $User = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => array('EMail' => $Call['Request']['EMail']) ));

        if (null !== $User)
        {
            $User = $User[0];

            $NewPassword = F::Live($Call['Password']['Generator']);

            F::Run('Entity', 'Update',
                array(
                     'Entity' => 'User',
                     'Where'  => $User['ID'],
                     'Data' => array ('Password' => $NewPassword)
                ));

            $Message['Scope'] = $User['EMail'];
            $Message['ID']    = 'Восстановление пароля';
            $Message['Data']  = F::Run('View', 'LoadParsed',
                                                 array(
                                                      'Scope' => 'User',
                                                      'ID' => 'Reset/EMail',
                                                      'Data' => array_merge($User, array('Password' => $NewPassword))
                                                 ));

            $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');

            F::Live($Call['Sender'], $Message);

            $Call['Output']['Content'][] = array(
                'Type' => 'Template',
                'Scope' => 'User',
                'Value' => 'Reset/Success'
            );
        }
        else
            $Call['Output']['Content'][] = array(
                'Type' => 'Template',
                'Scope' => 'User',
                'Value' => 'Reset/404'
            );

        return $Call;
    });

    self::setFn('GET', function ($Call)
    {
        $Call['Output']['Content'][] = array(
            'Type' => 'Template',
            'Scope' => 'User',
            'Value' => 'Reset/Form'
        );
        return $Call;
    });