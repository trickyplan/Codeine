<?php

    /* Codeine
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
            'ID'    => 'Odnoklassniki'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        return F::Run('System.Interface.HTTP', 'Redirect', $Call, ['Redirect' =>
            'http://www.odnoklassniki.ru/oauth/authorize?'
            .'client_id='.$Call['Odnoklassniki']['AppID']
            .'&scope='.$Call['Odnoklassniki']['Rights']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/auth/Odnoklassniki?BackURL='.$Call['Request']['BackURL'])
            .'&response_type=code'
        ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $Call = F::Hook('beforeOdnoklassnikiAuthenticate', $Call);
        if (isset($Call['Request']['code']))
        {
            $URL = 'http://api.odnoklassniki.ru/oauth/token.do';
			$params = array(
                'code' => $Call['Request']['code'],
                'redirect_uri' => urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/auth/Odnoklassniki?BackURL='.$Call['Request']['BackURL']),
                'grant_type' => 'authorization_code',
                'client_id' => $Call['Odnoklassniki']['AppID'],
                'client_secret' => $Call['Odnoklassniki']['Secret']
	        );

            $Result = F::Run('IO', 'Write',
                 [
                     'Storage'  => 'Web',
                     'Where'    => $URL,
                     'Output Format'   => 'Formats.JSON',
                     'Data'     => urldecode(http_build_query($params))
                 ]);


            $Result = array_pop($Result);

            if (isset($Result['access_token']))
            {
                $Odnoklassniki = F::Run('Code.Run.Social.Odnoklassniki', 'Run',
                    [
                        'Method' => 'users.getCurrentUser',
                        'Call'   =>
                        [
                            'access_token'  => $Result['access_token'],
                            'format'        => 'json',
                            'fields'        => 'uid, locale, first_name, last_name, name, gender, birthday, pic1024x768, pic640x480, location'
                        ]
                    ]);

                if (isset($Call['Session']['User']['ID']))
                {
                    $Call['User'] = F::Run('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'One'    => true,
                        'Where'  => $Call['Session']['User']['ID'],
                    ]);

                    $Call['Merge']['Social'] = 'Odnoklassniki';
                    $Call['Merge']['ID'] = $Odnoklassniki['uid'];
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
                            'Odnoklassniki.ID' => $Odnoklassniki['uid']
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
                            'Odnoklassniki' =>
                            [
                                'ID'     => $Odnoklassniki['uid'],
                                'Auth'   => $Result['access_token'],
                                'Active' => true
                            ],
                            'Status' => 1
                        ]
                    ]);
                }
                $Call = F::Hook('afterOdnoklassnikiIdentification', $Call);

                $Updated = $Call['User'];
                $Updated['Odnoklassniki'] =
                    [
                        'ID' => $Odnoklassniki['uid'],
                        'Auth'  => $Result['access_token'],
                        'token_type' => $Result['token_type'],
                        'Refresh' => $Result['refresh_token'],
                        'Expire' => time()+1800,
                        'Logged' => time(),
                        'Active' => true,
                    ];

                foreach ($Call['Odnoklassniki']['Mapping'] as $OdnoklassnikiField => $CodeineField)
                    if (isset($Odnoklassniki[$OdnoklassnikiField]) && !empty($Odnoklassniki[$OdnoklassnikiField]))
		                $Updated =  F::Dot($Updated, $CodeineField, $Odnoklassniki[$OdnoklassnikiField]);
                    else
                    {
                        $tempField = F::Dot($Odnoklassniki, $OdnoklassnikiField);
                        if (!empty($tempField))
                            $Updated = F::Dot($Updated, $CodeineField, $tempField);
                    }

                $Updated['Odnoklassniki']['Photo'] = html_entity_decode($Updated['Odnoklassniki']['Photo']);

                if (isset($Call['User']['Odnoklassniki']['LoginCount']))
                    $Call['User']['Odnoklassniki']['LoginCount']++;
                else
                    $Call['User']['Odnoklassniki']['LoginCount'] = 1;

                $Updated['Odnoklassniki']['LoginCount'] = $Call['User']['Odnoklassniki']['LoginCount'];

                if ($Updated['Odnoklassniki']['LoginCount'] == 1)
                    $Call = F::Hook('Odnoklassniki.FirstLogin', $Call);

                F::Run('Entity', 'Update', $Call,
                    [
                        'Entity' => 'User',
                        'Where'  =>
                        [
                            'ID' => $Call['User']['ID']
                        ],
                        'Data'   => $Updated
                    ]);
            }
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
                'Odnoklassniki' => -1
            ]
        ]);

        return $Call;
    });
