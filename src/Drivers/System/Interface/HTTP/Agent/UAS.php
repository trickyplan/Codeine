<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call) {
        return F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => $Call['UAS']['Host'],
                'Format' => 'Formats.JSON',
                'Data' =>
                    [
                        'uas' => urlencode($Call['HTTP']['Agent']),
                        'getJSON' => 'all'
                    ]
            ]
        );
    });