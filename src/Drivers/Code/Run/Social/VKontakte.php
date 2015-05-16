<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        /*$VKTS = microtime(true);

        $LastVKTS = F::Get('Last VK TS');
        F::Set('Last VK TS', $VKTS);

        if (($LastVKTS === null) or ($VKTS - $LastVKTS > (1/$Call['VKontakte']['Max Frequency'])))
            ;
        else
            usleep($VKTS - $LastVKTS);*/

        $Result = null;

        $Call = F::Hook('beforeVKontakteRun', $Call);

        $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);
        $Call['Call']['param_v'] = $Call['VKontakte']['Version'];
        $Call['Call']['lang'] = $Call['VKontakte']['Lang'];

        $Query = '?'.http_build_query($Call['Call']);

        $Result = F::Run('IO', 'Read',
               [
                   'Storage' => 'Web',
                   'Format'  => 'Formats.JSON',
                   'Where'   => $Call['VKontakte']['Entry Point'].'/'.$Call['Service'].'.'.$Call['Method'].$Query
               ]);

        $Result = array_pop($Result);

        if (isset($Result['response']))
        {
            if (isset($Call['Return Key']) && (F::Dot($Result['response'], $Call['Return Key']) !== null))
                $Result = F::Dot($Result['response'], $Call['Return Key']);
            else
                $Result = $Result['response'];
        }
        else
        {
            if (isset($Call['VKontakte']['Error']['Codes'][$Result['error']['error_code']]))
                F::Hook('VKontakte.'.$Call['VKontakte']['Error']['Codes'][$Result['error']['error_code']], $Call);
            else
                F::Log($Result['error']['error_code'].': '.$Result['error']['error_msg'], LOG_WARNING);

            $Result = null;
        }

        F::Hook('afterVKontakteRun', $Call);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        $Result = null;

        if (isset($Call['Data']['VKontakte']['Auth']))
        {
            F::Log('Used VK Token '.$Call['Data']['VKontakte']['Auth'].' from Data', LOG_INFO);
            $Result = $Call['Data']['VKontakte']['Auth'];
        }
        elseif (isset($Call['Session']['User']['VKontakte']['Auth']))
        {
            F::Log('Used VK Token '.$Call['Session']['User']['VKontakte']['Auth'].' from Session', LOG_INFO);
            $Result = $Call['Session']['User']['VKontakte']['Auth'];
        }
        else
            $Result = F::Run(null, 'Random Token', $Call, ['RTTL' => 1]);

        return $Result;
    });

    setFn('Random Token', function ($Call)
    {
        $Result = null;
        $TokenUsers =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'VKontakte.Active' => true
                            ],
                        'Sort' =>
                            [
                                'Modified' => true
                            ],
                        'Limit' =>
                        [
                            'From' => 0,
                            'To'   => $Call['VKontakte']['Token Users']
                        ]
                    ]);

            if (is_array($TokenUsers))
            {
                $RandomUser = $TokenUsers[array_rand($TokenUsers)];
                if (isset($RandomUser['VKontakte']['Auth']))
                {
                    $Result = $RandomUser['VKontakte']['Auth'];
                    F::Log('Used VK Token '.$RandomUser['VKontakte']['Auth'].' from '.count($TokenUsers).' random users', LOG_INFO);
                }
            }

        return $Result;
    });

    setFn('Remove Token', function ($Call)
    {
        if (isset($Call['Call']['access_token']))
        {
            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  =>
                    [
                        'VKontakte.Active' => true,
                        'VKontakte.Auth'   => $Call['Call']['access_token']
                    ],
                    'Skip Live' => true,
                    'Data'   =>
                        [
                            'VKontakte' =>
                                [
                                    'Active'    => false,
                                    'Auth'      => null
                                ]
                        ]
                ]);

            F::Log('Invalid token cleaned '.$Call['Call']['access_token'], LOG_INFO);
        }

        return $Call;
    });