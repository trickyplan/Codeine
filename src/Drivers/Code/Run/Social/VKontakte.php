<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Result = null;

        $Call = F::Hook('beforeVKontakteRun', $Call);

            $Call['Call']['param_v'] = $Call['VKontakte']['Version'];

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
                F::Log($Result['error']['error_msg'], LOG_ERR);

        F::Hook('afterVKontakteRun', $Call);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        if (isset($Call['Session']['User']['VKontakte']['Auth']) && !empty($Call['Session']['User']['VKontakte']['Auth']))
            $Token = $Call['Session']['User']['VKontakte']['Auth'];
        else
            $Token =
                    F::Run ('Entity', 'Read',
                        [
                            'Entity' => 'User',
                            'Where'  =>
                            [
                                'VKontakte.Auth' =>
                                [
                                    '$exists' => true
                                ]
                            ],
                            'Sort' =>
                            [
                                'Modified' => true
                            ],
                            'One' => true
                        ])['VKontakte']['Auth'];

        return $Token;
    });