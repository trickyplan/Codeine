<?php

    /* Codeine
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
            'ID'    => 'Odnoklassniki'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return F::Run('System.Interface.HTTP', 'Redirect', $Call, ['Location' => 
            'http://www.odnoklassniki.ru/oauth/authorize?'
            .'client_id='.$Call['Odnoklassniki']['AppID']
            .'&scope='.$Call['Odnoklassniki']['Rights']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Odnoklassniki')
            .'&response_type=code'
        ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $Call = F::Hook('beforeOdnoklassnikiAuthenticate', $Call);
file_put_contents("/home/alex/work/karmon.log",print_r( $Call['Request'], true).PHP_EOL, FILE_APPEND);
            if (isset($Call['Request']['code']))
            {
                $URL = 'http://api.odnoklassniki.ru/oauth/token.do';

                $Result = F::Run('IO', 'Write',
                     [
                         'Storage'  => 'Web',
                         'Where'    => $URL,
                         'Format'   => 'Formats.JSON',
                         'Data'     =>
                         [
                             'client_id' => $Call['Odnoklassniki']['AppID'],
                             'client_secret' => $Call['Odnoklassniki']['Secret'],
                             'code' => urlencode($Call['Request']['code']),
                             'grant_type' => 'authorization_code',
                             'redirect_uri' => urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Odnoklassniki')
                         ]
                     ]);

/*
                $URL = 'http://api.odnoklassniki.ru/oauth/token.do?''client_id='.$Call['Odnoklassniki']['AppID']
                .'&client_secret='.$Call['Odnoklassniki']['Secret'].'&code='.$Call['Request']['code'].'&grant_type=authorization_code'
                .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/authenticate/Odnoklassniki';
file_put_contents("/home/alex/work/karmon.log",print_r($URL, true).PHP_EOL, FILE_APPEND);
                $Result = F::Run('IO', 'Read',
                    [
                        'Storage' => 'Web',
                        'Format'  => 'Formats.JSON',
                        'Where' => $URL
                    ]);*/
file_put_contents("/home/alex/work/karmon.log",print_r($Result, true).PHP_EOL, FILE_APPEND);
/*                $Result = array_pop($Result);

                if (isset($Result['access_token']))
                {
                    if (isset($Call['Session']['User']['ID']))
                        $Call['User'] = F::Run('Entity', 'Read',
                        [
                            'Entity' => 'User',
                            'One'    => true,
                            'Where'  => $Call['Session']['User']['ID'],
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
                                    'fields'=> 'uid, first_name, last_name, nickname, screen_name, sex, bdate, city, country, timezone, photo, photo_medium, photo_big, photo_max, has_mobile, rate, contacts, education, online, counters'
                                ]
                            ])[0];

                    $Updated =
                    [
                        'VKontakte' =>
                        [
                            'ID' => $Result['user_id'],
                            'Auth'  => $Result['access_token']
                        ]
                    ];
                    foreach ($Call['VKontakte']['Mapping'] as $VKontakteField => $CodeineField)
                        if (isset($VKontakte[$VKontakteField]) && !empty($VKontakte[$VKontakteField]))
                $Updated =  F::Dot($Updated, $CodeineField, $VKontakte[$VKontakteField]);
                        else
                        {
                            $tempField = F::Dot($VKontakte, $VKontakteField);
                            if (!empty($tempField))
                                $Updated = F::Dot($Updated, $CodeineField, $tempField);
                        }

                    F::Run('Entity', 'Update', $Call,
                        [
                            'Entity' => 'User',
                            'Where'  =>
                            [
                                'ID' => $Call['User']['ID']
                            ],
                            'Data'   => $Updated
                        ]);
                }*/
            }

        $Call = F::Hook('afterOdnoklassnikiAuthenticate', $Call);

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
                'Odnoklassniki' =>
                [
                    'Auth' => null
                ]
            ]
        ]);

        return $Call;
    });
