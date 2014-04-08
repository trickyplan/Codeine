<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Links = [];

        foreach ($Call['Entities'] as $Handler => $Data)
            $Links [] =
                [
                    'sitemap' =>
                    [
                        'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Handler.'.xml',
                        'lastmod' => date(DATE_W3C)
                    ]
                ];

        $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => $Links];

        return $Call;
    });