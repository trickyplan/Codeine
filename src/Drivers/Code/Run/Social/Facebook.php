<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    setFn('Run', function ($Call)
    {
        if (isset($Call['Call']['access_token']))
            ;
        else
            $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);

        if (isset($Call['Call']))
            $Query = '?'.http_build_query($Call['Call']);
        else
            $Query = '';

        $URL = $Call['Facebook']['Entry Point'].$Call['Method'].$Query;
        $Result = F::Run('IO', 'Read',
               [
                   'Storage'    => 'Web',
                   'Format'     => 'Formats.JSON',
                   'Where'      => $URL
               ]);

        $Result = array_pop($Result);
        if (isset($Call['Return Key']) && F::Dot($Result, $Call['Return Key']))
            $Result = F::Dot($Result, $Call['Return Key']);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        $Result = null;

        if (isset($Call['Session']['User']['Facebook']['Auth']))
            $Result = $Call['Session']['User']['Facebook'];
        else
        {
            $Result = $Call['Data']['Facebook'] =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'Facebook.Auth' =>
                                [
                                    '$exists' => true
                                ]
                            ],
                        'Sort' => ['Modified' => false],
                        'One' => true
                    ])['Facebook'];
            if (isset($Result['Expire']) && $Result['Expire'] > time())
                ;
            else
            {
                $URL = 'https://graph.facebook.com/oauth/access_token';

                $ResultFB = F::Run('IO', 'Read',
                     [
                         'Storage'  => 'Web',
                         'Where'    => $URL,
                         'Data'     =>
                         [
                             'client_id' => $Call['Facebook']['AppID'],
                             'client_secret' => $Call['Facebook']['Secret'],
                             'grant_type' => 'fb_exchange_token',
                             'fb_exchange_token' => $Result['Auth']
                         ]
                     ]);
                $ResultFB = array_pop($ResultFB);
                parse_str($ResultFB, $ResultFB);
                if (isset($ResultFB['access_token']))
                {
                    /* F::Run ('Entity', 'Update',
                        [
                            'Entity' => 'User',
                            'Where'  =>
                                [
                                    'Facebook.Auth' =>$Result['Auth']
                                ],
	                            'Data' => 
	                            [
	                                'Facebook.Auth' => $ResultFB['access_token'],
	                                'Facebook.Expire' => time()+$ResultFB['expires']
	                            ],
                            'One' => true
                        ]); */
                    $Result['Auth'] = $ResultFB['access_token'];
                    $Result['Expire'] = $ResultFB['expires'];
                }
            }
        }

        return isset($Result['Auth'])?$Result['Auth']:'';
    });
