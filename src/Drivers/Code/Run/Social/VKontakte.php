<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $VKTS = microtime(true);

        $LastVKTS = F::Get('Last VK TS');
        F::Set('Last VK TS', $VKTS);

        if (($LastVKTS === null) or ($VKTS - $LastVKTS > (1/$Call['VKontakte']['Max Frequency'])))
            ;
        else
            usleep($VKTS - $LastVKTS);

        $Result = null;

        $Call = F::Hook('beforeVKontakteRun', $Call);

        $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);
        $Call['Call']['param_v'] = $Call['VKontakte']['Version'];

        $Query = '?'.http_build_query($Call['Call']);

        $Result = F::Run('IO', 'Read',
               [
                   'Storage' => 'Web',
                   'IO TTL'  => 86400,
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
            F::Hook('VKontakte.'.$Call['VKontakte']['Error']['Codes'][$Result['error']['error_code']], $Call);
            F::Log($Result['error']['error_msg'], LOG_INFO);

            $Result = null;
        }

        F::Hook('afterVKontakteRun', $Call);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        if (isset($Call['Data']['VKontakte']['Auth']))
        {
            F::Log('Used VK Token from Data', LOG_INFO);
            $Result = $Call['Data']['VKontakte']['Auth'];
        }
        elseif (isset($Call['Session']['User']['VKontakte']['Auth']))
        {
            F::Log('Used VK Token from Session', LOG_INFO);
            $Result = $Call['Session']['User']['VKontakte']['Auth'];
        }
        else
        {
            F::Log('Used VK Token from random users', LOG_INFO);
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

            $Token = $TokenUsers[array_rand($TokenUsers)]['VKontakte']['Auth'];
        }

        return $Token;
    });

    setFn('Remove Token', function ($Call)
    {
        if (isset($Call['Where']['ID']))
        {
            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  => $Call['Where']['ID'],
                    'Skip Live' => true,
                    'Data'   =>
                        [
                            'VKontakte' =>
                                [
                                    'Active' => false,
                                    'Auth'      => null
                                ]
                        ]
                ]);

            F::Log('Invalid token cleaned', LOG_INFO);
        }

        return $Call;
    });