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
        if (isset($Call['Request']['code']))
        {
                $URL = 'http://api.odnoklassniki.ru/oauth/token.do';
				$params = array(
	             'code' => $Call['Request']['code'],
                     'redirect_uri' => urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Odnoklassniki'),
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
			            'Method'    => 'users.getCurrentUser',
			            'Call'      =>
			            [
			                'access_token'  => $Result['access_token'],
			                'format'        => 'json',
			                'fields'        => 'uid, first_name, last_name, name, gender, age, birthday, photo_id, pic1024x768, location'
			            ]
		            ]);

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
                                    'ID'    => $Odnoklassniki['uid'],
                                    'Auth'  => $Result['access_token']
                                ],
                                'Status' => 1
                            ]
                        ]);
                    }

                    $Updated =
                    [
                        'Odnoklassniki' =>
                        [
                            'ID' => $Odnoklassniki['uid'],
                            'Auth'  => $Result['access_token'],
                            'token_type' => $Result['token_type'],
                            'Refresh' => $Result['refresh_token']
                        ]
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
                'Odnoklassniki' =>
                [
                    'Auth' => null
                ]
            ]
        ]);

        return $Call;
    });
