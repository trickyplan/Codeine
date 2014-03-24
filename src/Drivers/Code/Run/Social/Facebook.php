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