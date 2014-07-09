<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    setFn('Run', function ($Call)
    {
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
                        'One' => true
                    ])['Facebook'];

            if (isset($Result['Expire']) > time())
                ;
            else
            {
                $URL = 'https://graph.facebook.com/oauth/access_token';

                $Result = F::Run('IO', 'Read',
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

                $Result = array_pop($Result);
                parse_str($Result, $Result);
            }
        }




        return $Result;
    });