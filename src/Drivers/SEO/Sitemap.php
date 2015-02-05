<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('List Sitemap Indexes', function ($Call)
    {
        $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];
        $Call['Sitemap Indexes'] = [];

        foreach ($Call['Sitemap']['Handlers'] as $Name => $Handler)
        {
            $SitemapsCount = F::Run($Handler['Driver'], 'Sitemaps Count', $Call, $Handler);
            $IndexesCount = ceil($SitemapsCount/$Call['Sitemap']['Limits']['Sitemap Per Index']);

            for ($SI = 1; $SI <= $IndexesCount; $SI++)
            {
                $Call['Sitemap Indexes'][] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Name.'-'.$SI.'.xml';
                $Call['Output']['Content'][] =
                    [
                        'sitemap' =>
                            [
                                'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Name.'-'.$SI.'.xml'
                            ]
                    ];
            }
        }

        return $Call;
    });

    setFn('Show Sitemap Index', function ($Call)
    {
        $Call = F::Hook('beforeSitemapIndexShow', $Call);

            $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];

            $Handler = $Call['Sitemap']['Handlers'][$Call['Index']];
            $Call = F::Apply($Handler['Driver'], null, $Call, $Handler);

        $Call = F::Hook('afterSitemapIndexShow', $Call);

        return $Call;
    });

    setFn('Sitemap', function ($Call)
    {
        $Call = F::Hook('beforeSitemapShow', $Call);

            $Call['Output'] = ['Root' => 'urlset', 'Content' => []];

            $Handler = $Call['Sitemap']['Handlers'][$Call['Index']];
            $Call = F::Apply($Handler['Driver'], null, $Call, $Handler);

        $Call = F::Hook('afterSitemapShow', $Call);

        return $Call;
    });