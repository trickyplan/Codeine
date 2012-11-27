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
         array(
             'Storage' => 'Web',
             'Where' => 'http://loginza.ru/api/authinfo?token='.$Call['Request']['token'].'&id='.$Call['Loginza']['ID'].'&sig='.md5($Call['Request']['token'].$Call['Loginza']['Key'])
         ))[0], true);

        if (isset($Response['identity']))
        {
            // Проверить, есть ли такой пользователь
            $UserData = [
                        'EMail' => $Response['identity'],
                        'Status' => 1,
                        'Password' => sha1(rand()),
                        'Fullname' => isset($Response['name']['full_name'])
                                ? $Response['name']['full_name']
                                : $Response['name']['first_name'].' '.$Response['name']['last_name']];
            // FIXME

            foreach ($Call['Loginza']['Map'] as $Own => $Provider)
                $UserData[$Own] = F::Dot($Response, $Provider);

            $Call['User'] = F::Run('Entity','Read',
                array(
                    'Entity' => 'User',
                    'Where'  => [
                        'EMail' => $Response['identity']
                    ]
                ));

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
                    array(
                         'Entity' => 'User',
                         'Where' =>
                         array(
                             'EMail' => $Response['identity']
                         ),
                         'Data'  => $UserData
                    ))['Data'];

                F::Log('User authorized'.$Call['User']['ID'], LOG_INFO);
            }
        }
        else
               $Call['Output']['Content'][]
                        = array(
                        'Type' => 'Template',
                        'Scope' => 'User',
                        'ID' => 'Failed'
                    );


        return $Call;
    });