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
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('ByID', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call, array('Entity' => 'User'))[0];

        if (!empty($Call['User']))
        {
            $NewPassword = F::Live($Call['Password']['Generator']);

            F::Run('Entity', 'Update',
                [
                      'Entity' => 'User',
                      'Purpose' => 'Reset',
                      'Where'  => $Call['User']['ID'],
                      'Data' =>
                       [
                            [
                                'Password' => $NewPassword
                            ]
                       ]
                ]);

            $Message['Scope'] = $Call['User']['EMail'];
            $Message['ID']    = 'Восстановление пароля';
            $Message['Data']  = F::Run('View', 'Load',
                                                 array(
                                                      'Scope' => 'User',
                                                      'ID' => 'Reset/EMail',
                                                      'Data' => array_merge($Call['User'], array('Password' => $NewPassword, 'Host' => $Call['Host']))
                                                 ));

            $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');

            F::Live($Call['Sender'], $Message);

            $Call['Output']['Content'][] = array(
                'Type' => 'Template',
                'Scope' => 'User',
                'ID' => 'Reset/Success'
            );
        }
        else
            $Call['Output']['Content'][] = array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Reset/404'
                );

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        return F::Run(null, 'ByID', $Call, array('Where' => array('EMail' => $Call['Request']['EMail'])));
    });

    setFn('GET', function ($Call)
    {
        $Call['Output']['Content'][] = array(
            'Type' => 'Template',
            'Scope' => 'User',
            'ID' => 'Reset/Form'
        );
        return $Call;
    });