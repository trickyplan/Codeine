<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Identificate', function ($Call)
    {
        // Example: http://loginza.ru/api/authinfo?token=[TOKEN_KEY_VALUE]&id=[WIDGET_ID]&sig=[API_SIGNATURE]

        d(__FILE__, __LINE__, $Call);

        $Call['Response'] = json_decode(file_get_contents(
                'http://loginza.ru/api/authinfo?token='
                    .$Call['Request']['token']
                    .'&id=17193&sig='.md5($Call['Request']['token'].'6a0f06ccf3538d42ed12f5b569710c81')), true) ;

        if (isset($Call['Response']['identity']))
            $Call = F::Run(null, 'Authenticate', $Call);
        else
            d(__FILE__, __LINE__, $Call['Response']);

        return $Call;
    });

    self::setFn('Authenticate', function ($Call)
    {
        $User = F::Run('Entity', 'Read',
            array(
                 'Entity' => 'User',
                 'Where' => array ('Login' => $Call['Response']['identity'])
            ));

        if (empty($User))
        {
            F::Run('Entity', 'Create',
                 array(
                      'Entity' => 'User',
                      'Data' => array(
                          'Login' => $Call['Response']['identity'],
                          'Password' => '',
                          'EMail' => ''
                      )
                 ));
        }
        else
        {
            F::Run('Auth', 'Register',
                array(
                     'User' => $User
                ));
        }


        return $Call;
    });