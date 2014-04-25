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
            'ID'    => 'VKontakte'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' =>
            'https://oauth.vk.com/authorize?client_id='
            .$Call['VKontakte']['AppID']
            .'&scope='.$Call['VKontakte']['Rights']
            .'&display=popup'
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/VKontakte')
            .'&response_type=code'
            .'&v='.$Call['VKontakte']['Version']
        ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://oauth.vk.com/access_token?client_id='.$Call['VKontakte']['AppID']
            .'&client_secret='.$Call['VKontakte']['Secret'].'&code='.$Call['Request']['code']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/authenticate/VKontakte';

        $Result = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Format'  => 'Formats.JSON',
                'Where' => $URL
            ]);

        $Result = array_pop($Result);

        if (isset($Result['access_token']))
        {
            if (isset($Call['Session']['User']['ID']))
                $Call['User'] = F::Run('Entity', 'Update', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Where'  => $Call['Session']['User']['ID'],
                    'Data'   =>
                    [
                        'VKontakte.ID' => $Result['user_id']
                    ]
                ]);
            else
                $Call['User'] = F::Run('Entity', 'Read',
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Sort'   =>
                    [
                        'ID' => SORT_ASC
                    ],
                    'Where'  =>
                    [
                        'VKontakte.ID' => $Result['user_id']
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
                        'VKontakte' =>
                        [
                            'ID'    => $Result['user_id'],
                            'Auth'  => $Result['access_token']
                        ],
                        'Status' => 1
                    ]
                ]);
            }

            $VKontakte = F::Run('Code.Run.Social.VKontakte', 'Run',
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

            $Updated =
            [
                'VKontakte' =>
                [
                    'Auth'  => $Result['access_token']
                ]
            ];

            foreach ($Call['VKontakte']['Mapping'] as $VKontakteField => $CodeineField)
                if (isset($VKontakte[$VKontakteField]) && !empty($VKontakte[$VKontakteField]))
                    $Updated[$CodeineField] = $VKontakte[$VKontakteField];

            F::Run('Entity', 'Update', $Call,
                [
                    'Entity' => 'User',
                    'Where'  =>
                    [
                        'VKontakte.ID' => $Result['user_id']
                    ],
                    'Data'   => $Updated
                ]);
        }

        return $Call;
    });

    setFn('Annulate', function ($Call)
    {
        F::Run('Entity', 'Update', $Call,
        [
            'Entity' => 'User',
            'Where'  => $Call['Session']['User']['ID'],
            'Data'   =>
            [
                'VKontakte' =>
                [
                    'ID' => null,
                    'Auth' => null
                ]
            ]
        ]);

        return $Call;
    });