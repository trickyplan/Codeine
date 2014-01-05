<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Sitemap']['Links'] =
        [
            [
                'url' =>
                [
                    'loc'         => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/',
                    'changefreq'  => 'daily',
                    'priority'    => 1
                ]
            ]
        ];

        foreach ($Call['Entities'] as $Call['Entity'] => $Data)
        {
            $SubLinks = F::Run($Call['Entity'].'.Sitemap', 'Generate', $Call);
                foreach ($SubLinks as $Sublink)
                    $Call['Sitemap']['Links'][] = [
                                    'url' =>
                                    [
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Data['Frequency'],
                                      'priority'    => $Data['Priority']
                                    ]
                               ];
        }

        $Call['Output'] =  ['Root' => 'urlset', 'Content' => $Call['Sitemap']['Links']];

        return $Call;
    });