<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Identificate', function ($Call)
    {
        $URL = 'https://oauth.vk.com/authorize?client_id='.$Call['VK']['AppID'].'&scope='
            .$Call['VK']['Rights'].'&display=popup&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/oauth/VK').'&response_type=code&v='.$Call['VK']['Version'];

        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $URL]);

        return $Call;

    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://oauth.vk.com/access_token?client_id='.$Call['VK']['AppID']
            .'&client_secret='.$Call['VK']['Secret'].'&code='.$Call['Request']['code']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/oauth/VK';

        $Result = json_decode(F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => $URL
            ])[0], true);

        if (isset($Result['access_token']))
            F::Run('Entity','Update',
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User']['ID'],
                    'Data' =>
                        [
                            'VK' =>
                            [
                                'Auth' => $Result['access_token'],
                                'ID' => $Result['user_id']
                            ]
                        ]
                ]);

        $Call = F::Hook('afterVKAuthenticate', $Call);

        return $Call;
    });

    setFn('Run', function ($Call)
    {
        if (isset($Call['Data']['VK']['Auth']) && !empty($Call['Data']['VK']['Auth']))
            $Call['Call']['access_token'] = $Call['Data']['VK']['Auth'];

        $Result = json_decode(F::Run('IO', 'Read',
               [
                   'Storage' => 'Web',
                   'Where' =>
                   $Call['VK']['Entry Point'].'/'.$Call['Method'].'?'.http_build_query($Call['Call'])
               ])[0], true);

        if (isset($Result['response']))
        {
            if (isset($Call['Key']) && (F::Dot($Result['response'], $Call['Key']) !== null))
                return F::Dot($Result['response'], $Call['Key']);
            else
                return $Result['response'];
        }
        else
        {
            F::Log($Result['error'], LOG_ERR);
            return null;
        }
    });

    setFn('Annulate', function ($Call)
    {
        F::Run('Entity','Update', $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User']['ID'],
                    'Data' => ['VK' => ['Auth' => 0]]
                ]);

        $Call = F::Hook('afterVKAnnulate', $Call);

        return $Call;
    });