<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
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
                return F::Dot($Result['response'], $Call['Return Key']);
            else
                return $Result['response'];
        }
        else
        {
            F::Log($Result['error'], LOG_ERR);
            return null;
        }
    });