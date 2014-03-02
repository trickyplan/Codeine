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
            $Query = http_build_query($Call['Call']);
        else
            $Query = '';

        $Result = F::Run('IO', 'Read',
               [
                   'Storage' => 'Web',
                   'Format'  => 'Formats.JSON',
                   'Where'   => $Call['VK']['Entry Point'].'/'.$Call['Service'].'.'.$Call['Method'].'?'.$Query
               ])[0];

        if (isset($Result['response']))
        {
            if (isset($Call['Key']) && (F::Dot($Result['response'], $Call['Key']) !== null))
                return F::Dot($Result['response'], $Call['Key']);
            else
                return $Result['response'];
        }
        else
        {
            F::Log($Result['error'], LOG_ERR);
            return null;
        }
    });
/*
    setFn('Annulate', function ($Call)
    {
        F::Run('Entity','Update', $Call,
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User']['ID'],
                    'Data' => ['VK' => ['Auth' => 0]]
                ]);

        $Call = F::Hook('afterVKAnnulate', $Call);

        return $Call;
    });*/