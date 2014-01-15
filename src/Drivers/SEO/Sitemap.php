<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeSitemap', $Call);
            $Call = F::Run('SEO.Sitemap.'.$Call['Sitemap']['Mode'], null, $Call);
        $Call = F::Hook('afterSitemap', $Call);

        return $Call;
    });

    setFn('Ping', function ($Call)
    {
        $SitemapURL = '/ping?sitemap='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap.xml');

        $Pings = [];

        foreach($Call['SearchEngines'] as $Name => $URL)
            $Pings[$Name] = $URL.$SitemapURL;

        F::Run('IO', 'Read',
        [
             'Storage' => 'Web',
             'Where' =>
             [
                 'ID' => $Pings
             ]
        ]);


        return $Call;
    });
