<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Root'] = 'OpenSearchDescription';
        $Call['Namespace'] = 'http://a9.com/-/spec/opensearch/1.1/';

        $Call['Output']['Content']['ShortName'] = 'Поиск по '.$Call['Project']['Title'];
        $Call['Output']['Content']['Description'] = $Call['Project']['Description']['Short'];

        if(isset($Call['Project']['Contacts']['Search']['EMail']))
            $Call['Output']['Content']['Contact'] = $Call['Project']['Contacts']['Search']['EMail'];

        $Call['Output']['Content']['Image'] =
            [
                '@height' => 48,
                '@width'  => 48,
                '@type'   => 'image/vnd.microsoft.icon',
                $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/favicon.ico'
            ];

        $Call['Output']['Content']['Url'] =
            [
                [
                    '@type' => 'application/x-suggestions+json',
                    '@template' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/search.json?Query={searchTerms}'
                ],
                [
                    '@type' => 'text/html',
                    '@rel'  => 'results',
                    '@template' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/search?Query={searchTerms}'
                ]
            ];

        return $Call;
    });