<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Run', function ($Call)
    {
        if (isset($Call['Call']['access_token']))
            ;
        else
            $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);

        if (!isset($Call['Call']['locale']))
            $Call['Call']['locale'] = $Call['Facebook']['Default Locale'];

        if (isset($Call['Call']))
            $Query = '?'.http_build_query($Call['Call']);
        else
            $Query = '';

        $URL = $Call['Facebook']['Entry Point'].$Call['Method'].$Query;

        $Result = F::Run($Call['Backend']['Service'], $Call['Backend']['Method'], 
                         $Call['Backend']['Options'],
               [
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

        if (isset($Call['Data']['Facebook']['Auth']))
        {
            F::Log('Used FB Token from Data', LOG_INFO);
            $Result['Auth'] = $Call['Data']['Facebook']['Auth'];
        }
        elseif (isset($Call['Session']['User']['Facebook']['Auth']))
        {
            F::Log('Used FB Token from Session', LOG_INFO);
            $Result['Auth'] = $Call['Session']['User']['Facebook']['Auth'];
        }
        else
        {
            F::Log('Used FB Token from random users', LOG_INFO);
            $Result = $Call['Data']['Facebook'] =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'Facebook.Active' => true
                            ],
                        'Limit' =>
                        [
                            'From' => 0,
                            'To'   => 1
                        ],
                        'Sort' => ['Modified' => false],
                        'One' => true
                    ])['Facebook'];

            if (isset($Result['Expire']) && $Result['Expire'] > time())
                ;
            elseif (isset($Result['Auth']))
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
                    F::Set(REQID, $ResultFB['access_token']);
/*                    F::Run ('Entity', 'Update',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                        [
                            'Facebook.ID' =>$Result['ID']
                        ],
                        'Data' =>
                        [
                            'Facebook' =>
                            [
                                'Auth' => $ResultFB['access_token'],
                                'Expire' => time()+$ResultFB['expires']
                            ]
                        ],
                        'No'  => ['beforeEntityWrite' => true],
                        'One' => true
                    ]);*/
                    F::Run ('Code.Run.Delayed', 'Run',
                        [
                            'Delayed Mode' => 'Dirty',
                            'Run' =>
                            [
                                'Service'=> 'Entity',
                                'Method'=> 'Update',
                                'Call'=>
                                [
                                    'Entity'=> 'User',
                                    'Where'=> ['Facebook.ID' => $Result['ID']],
                                    'Data'=>  [
                                                'Facebook' =>
                                                [
                                                    'Auth' => $ResultFB['access_token'],
                                                    'Expire' => time()+$ResultFB['expires']
                                                ]
                                            ],
                                    'No'  => ['beforeEntityWrite' => true]
                                ]
                            ]
                        ]);

                    $Result['Auth'] = $ResultFB['access_token'];
                    $Result['Expire'] = $ResultFB['expires'];
                }
            }
            else
            {
                $Result['Auth'] = '';
            }
        }

        return isset($Result['Auth'])? $Result['Auth']: '';
    });
