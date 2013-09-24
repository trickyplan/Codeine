<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Sitemap']['Mode']))
            $Call['Sitemap']['Mode'] = 'Index';

        return F::Run(null, $Call['Sitemap']['Mode'], $Call);
    });

    setFn('Index', function ($Call)
    {
        $Links = [];

        foreach ($Call['Entities'] as $Handler => $Data)
            $Links[] =
                [
                    'sitemap' =>
                    [
                        'loc' => $Call['Host'].'/sitemap/'.$Handler.'.xml'
                        // TODO lastmod
                    ]
                ];

        $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => $Links];

        return $Call;
    });

    setFn('Combined', function ($Call)
    {
        $Links =
        [
            [
                'url' =>
                [
                    'loc'         => $Call['Host'].'/',
                    'changefreq'  => 'daily',
                    'priority'    => 1
                ]
            ]
        ];

        foreach ($Call['Entities'] as $Call['Entity'] => $Data)
        {
            $SubLinks = F::Run($Call['Entity'].'.Sitemap', 'Generate', $Call);
                foreach ($SubLinks as $Sublink)
                    $Links[] = [
                                    'url' =>
                                    [
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Data['Frequency'],
                                      'priority'    => $Data['Priority']
                                    ]
                               ];
        }

        $Call['Output'] =  ['Root' => 'urlset', 'Content' => $Links];

        return $Call;
    });

    setFn('Entity', function ($Call)
    {
        $Call['Links'] = [];

        $SubLinks = F::Run($Call['Entity'].'.Sitemap', 'Generate', $Call);

        foreach ($SubLinks as $Sublink)
            $Call['Links'][] = [
                                  'url' =>
                                  [
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Call['Entities'][$Call['Entity']]['Frequency'],
                                      'priority'    => $Call['Entities'][$Call['Entity']]['Priority']
                                  ]
                               ];

        $Call['Output'] =  ['Root' => 'urlset', 'Content' => $Call['Links']];

        return $Call;
    });

    setFn('Ping', function ($Call)
    {
        foreach($Call['SearchEngines'] as $Name=> $URL)
            $Call['Responses'][$Name] = F::Run('IO', 'Read',
                [
                     'Storage' => 'Web',
                     'ID' => $URL.'/ping?sitemap='.urlencode($Call['Host'].'/sitemap.xml')
                ]);

        return $Call;
    });
