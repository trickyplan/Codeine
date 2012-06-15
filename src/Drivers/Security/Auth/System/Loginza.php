<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Identificate', function ($Call)
    {

    });

    self::setFn('Authentificate', function ($Call)
    {
        $Response = json_decode(F::Run('IO', 'Read',
         array(
             'Storage' => 'Web',
             'Where' => 'http://loginza.ru/api/authinfo?token='.$Call['Request']['token'].'&id=24623&sig='.md5($Call['Request']['token'].'1dc118bc34d783e2117a7b826fccbe2a')
         ))[0], true);

        if (isset($Response['identity']))
        {
            // Проверить, есть ли такой пользователь

            $Call['User'] = F::Run('Entity','Read',
                array(
                    'Entity' => 'User',
                    'Where'  => array(
                        'Login' => $Response['identity']
                    )
                ))[0];

            // Если нет, зарегистрировать
            if (empty($Call['User']))
            {
                $Call['User'] = F::Run('Entity','Create',
                    array(
                        'Entity' => 'User',
                        'Debug' => true,
                        'Data'  => array(
                            'Login' => $Response['identity'],
                            'Status' => 1,
                            'Photo' => $Response['photo'],
                            'Fullname' => isset($Response['name']['full_name'])
                                ? $Response['name']['full_name']
                                : $Response['name']['first_name'].' '.$Response['name']['last_name'],
                            'EMail' => $Response['email']
                        )
                    ));
            }

        }

        return $Call;
    });