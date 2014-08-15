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
					$URL = "http://api.odnoklassniki.ru/fb.do";
					$sign = md5("application_key={$Call['Odnoklassniki']['Public']}format=jsonmethod=users.getCurrentUser" . md5("{$Result['access_token']}{$Call['Odnoklassniki']['Secret']}"));
				 
					$params = array(
						'method'          => 'users.getCurrentUser',
						'access_token'    => $Result['access_token'],
						'application_key' => $Call['Odnoklassniki']['Public'],
						'format'          => 'json',
						'sig'             => $sign
					);
				    $Odnoklassniki = F::Run('IO', 'Read',
				     [
				         'Storage'  => 'Web',
				         'Where'    => $URL.'?'.urldecode(http_build_query($params)),
				         'Output Format'   => 'Formats.JSON'/*,
				         'Format'   => 'Formats.JSON'/*,
				         'Data'     => urldecode(http_build_query($params))*/
				     ])[0];

/*                    if (isset($Call['Session']['User']['ID']))
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
                                'Odnoklassniki.ID' => $Result['user_id']
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
                                    'ID'    => $Result['user_id'],
                                    'Auth'  => $Result['access_token']
                                ],
                                'Status' => 1
                            ]
                        ]);
                    }

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
*/
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
