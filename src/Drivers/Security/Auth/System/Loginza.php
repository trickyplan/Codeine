<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Identificate', function ($Call)
    {
        if ($Call['Loginza']['ID'] == 0)
            $Call = F::Hook('Loginza.NotConfigured', $Call);

        $Call['LoginzaURL'] = urlencode($Call['Host'].'/auth/social'); // FIXME
        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Response = json_decode(F::Run('IO', 'Read',
         [
             'Storage' => 'Web',
             'Where'   =>
                 'http://loginza.ru/api/authinfo?token='
                 .$Call['Request']['token']
                 .'&id='.$Call['Loginza']['ID']
                 .'&sig='.md5($Call['Request']['token'].$Call['Loginza']['Key'])
         ])[0], true);

        if (isset($Response['identity']))
        {
            $UserData = [
                        'External' => parse_url($Response['provider'], PHP_URL_HOST),
                        'EMail' => $Response['identity'],
                        'Status' => 1,
                        'Password' => sha1(rand()),
                        'Fullname' => isset($Response['name']['full_name'])
                                ? $Response['name']['full_name']
                                : $Response['name']['first_name'].' '.$Response['name']['last_name']];
            // FIXME

            foreach ($Call['Loginza']['Map'] as $Own => $Provider)
                $UserData[$Own] = F::Dot($Response, $Provider);

            // Проверить, есть ли такой пользователь

            $Call['User'] = F::Run('Entity','Read',
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Where'  => [
                        'EMail' => $Response['identity']
                    ]
                ]);

            // Если нет, зарегистрировать
            if (empty($Call['User']))
            {
                $Call['User'] = F::Run('Entity','Create',
                    [
                        'Entity' => 'User',
                        'Data'  => $UserData
                    ])['Data'];

                F::Log('User registered '.$Call['User']['ID'], LOG_INFO);
            }
            else
            {
                $Call['User'] = F::Run('Entity','Update',
                    [
                         'Entity' => 'User',
                         'Where' =>
                         [
                             'ID' => $Call['User']['ID']
                         ],
                         'Data'  => $UserData
                    ])['Data'];

                F::Log('User authorized '.$Call['User']['ID'], LOG_INFO);
            }
        }
        else
               $Call['Output']['Content'][] =
                   [
                        'Type' => 'Template',
                        'Scope' => 'User/Authenticate',
                        'ID' => 'Failed'
                   ];

        return $Call;
    });