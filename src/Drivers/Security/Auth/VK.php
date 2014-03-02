<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] =
        [
            'Type'  => 'Template',
            'Scope' => 'Security.Auth',
            'ID'    => 'VK'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' =>
            'https://oauth.vk.com/authorize?client_id='
            .$Call['VK']['AppID']
            .'&scope='.$Call['VK']['Rights']
            .'&display=popup'
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/VK')
            .'&response_type=code'
            .'&v='.$Call['VK']['Version']
        ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://oauth.vk.com/access_token?client_id='.$Call['VK']['AppID']
            .'&client_secret='.$Call['VK']['Secret'].'&code='.$Call['Request']['code']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/authenticate/VK';

        $Result = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Format'  => 'Formats.JSON',
                'Where' => $URL
            ]);

        $Result = array_pop($Result);

        if (isset($Result['access_token']))
        {
            $Call['User'] = F::Run('Entity', 'Read', $Call,
            [
                'Entity' => 'User',
                'One'    => true,
                'Where'  =>
                [
                    'VK.ID' => $Result['user_id']
                ]
            ]);

            if (empty($Call['User']))
            {
                $Call['User'] = F::Run('Entity', 'Create', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Data'  =>
                    [
                        'VK' =>
                        [
                            'ID'    => $Result['user_id'],
                            'Auth'  => $Result['access_token']
                        ],
                        'Status' => 1
                    ]
                ]);
            }

            $VK = F::Run('Code.Run.Social.VK', 'Run',
                    [
                        'Service'   => 'users',
                        'Method'    => 'get',
                        'Call'      =>
                        [
                            'uids'  => $Result['user_id'],
                            'access_token'  => $Result['access_token'],
                            'fields'=> 'uid, first_name, last_name, nickname, screen_name, sex, bdate (birthdate), city, country, timezone, photo, photo_medium, photo_big, has_mobile, rate, contacts, education, online, counters'
                        ]
                    ])[0];

            $Updated = [];

            foreach ($Call['VK']['Mapping'] as $VKField => $CodeineField)
                if (isset($VK[$VKField]) && !empty($VK[$VKField]))
                    $Updated[$CodeineField] = $VK[$VKField];

            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  =>
                    [
                        'VK.ID' => $Result['user_id']
                    ],
                    'Data'   => $Updated
                ]);
        }

        return $Call;
    });