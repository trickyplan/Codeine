<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Index', function ($Call)
    {
        $Call = F::Hook('beforeSitemap', $Call);

            $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];

            foreach ($Call['Sitemap']['Handlers'] as $HandlerCall)
            {
                $HandlerCall['Method'] = 'Index';
                $Call = F::Live($HandlerCall, $Call);
            }

        $Call = F::Hook('afterSitemap', $Call);

        return $Call;
    });

    setFn('One', function ($Call)
    {
        $Call = F::Hook('beforeSitemap', $Call);

            $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];

            foreach ($Call['Sitemap']['Handlers'] as $HandlerCall)
            {
                $HandlerCall['Method'] = 'One';
                $Call = F::Live($HandlerCall, $Call);
            }

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
