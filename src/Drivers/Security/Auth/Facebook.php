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
            'ID'    => 'Facebook'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return F::Run('System.Interface.HTTP', 'Redirect', $Call,
            [
                'Location' => 'https://www.facebook.com/dialog/oauth?client_id='.$Call['Facebook']['AppID'].'&scope='
            .$Call['Facebook']['Rights'].'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Facebook').'&response_type=code'
            ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://graph.facebook.com/oauth/access_token?client_id='.$Call['Facebook']['AppID']
            .'&client_secret='.$Call['Facebook']['Secret'].'&code='.$Call['Request']['code']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/authenticate/Facebook';

        $Result = F::Run('IO', 'Read',
             [
                 'Storage'  => 'Web',
                 'Where'    => $URL
             ]);

        $Result = array_pop($Result);
        parse_str($Result, $Result);

        if (isset($Result['access_token']))
        {
            $Facebook = F::Run('Code.Run.Social.Facebook', 'Run',
                    [
                        'Method'    => '/me',
                        'Call'      =>
                        [
                            'access_token'  => $Result['access_token']
                        ]
                    ]);

            $Updated = [];
            if (isset($Call['Session']['User']['ID']))
            {
                $Call['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Where'  => $Call['Session']['User']['ID']
                ]);

                $Gemini = F::Run('Entity', 'Read',
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Sort'   =>
                    [
                        'ID' => SORT_ASC
                    ],
                    'Where'  =>
                    [
                        'ID' => ['$ne' => $Call['User']['ID']],
                        'Facebook.ID' => $Facebook['id']
                    ]
                ]);
                if (isset($Gemini))
                {
                    // merge review
                    F::Run('Entity', 'Update',
                    [
                        'Entity' => 'Review',
                        'Where'  => 
                        [
                            'User'   => $Gemini['ID'],
                            'Object' => ['$ne' => $Call['User']['ID']]
                        ],
                        'Data'   => ['User' => $Call['User']['ID']]
                    ],
            	    ['One' => false]);

                    F::Run('Entity', 'Update',
                    [
                        'Entity' => 'Review',
                        'Where'  => 
                        [
                            'Object' => $Gemini['ID'],
                            'User'   => ['$ne' => $Call['User']['ID']]
                        ],
                        'Data'   => ['Object' => $Call['User']['ID']]
                    ],
            	    ['One' => false]);

                    F::Run('Entity', 'Delete',
                    [
                        'Entity' => 'Review',
                        'Where'  => ['User'   => $Gemini['ID']]
                    ]);
                    F::Run('Entity', 'Delete',
                    [
                        'Entity' => 'Review',
                        'Where'  => ['Object'   => $Gemini['ID']]
                    ]);

                    // merge comments
                    F::Run('Entity', 'Update',
                    [
                        'Entity' => 'Comment',
                        'Where'  => ['User' => $Gemini['ID']],
                        'Data'   => ['User' => $Call['User']['ID']]
                    ],
                    ['One' => false]);

                    // merge user data
                    foreach ($Call['VKontakte']['MergeMapping'] as $MergeField => $CodeineField)
                        if (isset($Gemini[$MergeField]) && !empty($Gemini[$MergeField]) && !isset($Call['User'][$MergeField]['Auth']))  
                      	    $Updated[$CodeineField] = $Gemini[$MergeField];

                    F::Run('Entity', 'Delete',
                    [
                        'Entity' => 'User',
                        'Where'  => $Gemini['ID']
                    ]);
                }
            }
            else
                $Call['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Sort'   =>
                    [
                        'ID' => true
                    ],
                    'Where'  =>
                    [
                        'Facebook.ID' => $Facebook['id']
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
                        'Facebook' =>
                        [
                            'ID'    => $Facebook['id'],
                            'Auth'  => $Result['access_token']
                        ],
                        'Status' => 1
                    ]
                ]);
            }

            $Updated['Facebook'] =
                [
                    'ID'     => $Facebook['id'],
                    'Auth'   => $Result['access_token'],
                    'Expire' => time()+$Result['expires']
                ];

            foreach ($Call['Facebook']['Mapping'] as $FacebookField => $CodeineField)
                if (isset($Facebook[$FacebookField]) && !empty($Facebook[$FacebookField]))
                    $Updated = F::Dot($Updated, $CodeineField,$Facebook[$FacebookField]);

            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  => $Call['User']['ID'],
                    'Data'   => $Updated
                ]);
        }

        $Call = F::Hook('afterFacebookAuthenticate', $Call);

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
                'Facebook' =>
                [
                    'Auth' => null
                ]
            ]
        ]);

        return $Call;
    });
