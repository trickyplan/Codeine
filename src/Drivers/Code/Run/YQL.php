<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Request = urlencode($Call['Run']);

        $Result = F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Format'  => 'Formats.JSON',
            'Where' =>
            [
                'ID' => $Call['YQL Endpoint'].'?q='.$Request.'&format=json'
            ]
        ]);

        return array_pop($Result);
    });