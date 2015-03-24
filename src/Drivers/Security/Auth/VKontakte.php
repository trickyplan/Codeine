<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
        // One day fix
        return F::Run('System.Interface.HTTP', 'Redirect', $Call, ['Location' =>
            'https://oauth.vk.com/authorize?client_id='
            .$Call['VKontakte']['AppID']
            .'&scope='.$Call['VKontakte']['Rights']
            .'&display=popup'
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/VKontakte?BackURL='.$Call['Request']['BackURL'])
            .'&response_type=code'
            .'&v='.$Call['VKontakte']['Version']
        ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $Call = F::Hook('beforeVKontakteAuthenticate', $Call);

        if (isset($Call['Request']['code']))
        {
            $URL = 'https://oauth.vk.com/access_token?client_id='.$Call['VKontakte']['AppID']
                .'&client_secret='.$Call['VKontakte']['Secret'].'&code='.$Call['Request']['code']
                .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/authenticate/VKontakte?BackURL='.$Call['Request']['BackURL'];

            $Result = F::Run('IO', 'Read',
                [
                    'Storage' => 'Web',
                    'Format'  => 'Formats.JSON',
                    'Where' => $URL
                ]);

            $Result = array_pop($Result);

            if (isset($Result['access_token']))
            {
                $Updated = [];

                if (isset($Call['Session']['User']['ID']))
                {
                    $Call['User'] = F::Run('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'One'    => true,
                        'Where'  => $Call['Session']['User']['ID'],
                    ]);

                    $Call['Merge']['Social'] = 'VKontakte';
                    $Call['Merge']['ID'] = $Result['user_id'];

                    $Call = F::Hook('socialMerge', $Call);

                    if (isset($Call['Merge']['Updated']))
                        $Updated = $Call['Merge']['Updated'];
                }
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

                $Call = F::Hook('afterVKontakteIdentification', $Call);

                $Updated['VKontakte'] =
                    [
                        'Active' => true,
                        'ID' => $Result['user_id'],
                        'Auth'  => $Result['access_token'],
                        'Logged' => time()
                    ];

                if (isset($Call['User']['VKontakte']['LoginCount']))
                    $Call['User']['VKontakte']['LoginCount']++;
                else
                    $Call['User']['VKontakte']['LoginCount'] = 1;

                $Updated['VKontakte']['LoginCount'] = $Call['User']['VKontakte']['LoginCount'];

                if ($Updated['VKontakte']['LoginCount'] == 1)
                    $Call = F::Hook('VKontakte.FirstLogin', $Call);

                F::Run('Code.Run.Delayed', 'Run',
                    [
                        'Run' =>
                            [
                                'Service' => 'Entity',
                                'Method' => 'Update',
                                'Call' =>
                                    [
                                        'Entity' => 'User',
                                        'Where' =>
                                            [
                                                'ID' => $Call['User']['ID']
                                            ],
                                        'Data' => $Updated
                                    ]
                            ]
                    ]
                );;
            }
        }

        $Call = F::Hook('afterVKontakteAuthenticate', $Call);

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
                    'Active' => false,
                    'Auth' => ''
                ]
            ]
        ]);

        return $Call;
    });
