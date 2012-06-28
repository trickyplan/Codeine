<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeReset', $Call);
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('ByID', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call, array('Entity' => 'User'));

        if (!empty($Call['User']))
        {
            list($Call['User']) = $Call['User'];
            $NewPassword = F::Live($Call['Password']['Generator']);

            F::Run('Entity', 'Set',
                array(
                     'Entity' => 'User',
                     'Where'  => $Call['User']['ID'],
                     'Data' => array ('Password' => sha1($NewPassword)) // FIXME
                ));

            $Message['Scope'] = $Call['User']['EMail'];
            $Message['ID']    = 'Восстановление пароля';
            $Message['Data']  = F::Run('View', 'LoadParsed',
                                                 array(
                                                      'Scope' => 'User',
                                                      'ID' => 'Reset/EMail',
                                                      'Data' => array_merge($Call['User'], array('Password' => $NewPassword))
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

    self::setFn('POST', function ($Call)
    {
        return F::Run(null, 'ByID', $Call, array('Where' => array('EMail' => $Call['Request']['EMail'])));
    });

    self::setFn('GET', function ($Call)
    {
        $Call['Output']['Content'][] = array(
            'Type' => 'Template',
            'Scope' => 'User',
            'ID' => 'Reset/Form'
        );
        return $Call;
    });