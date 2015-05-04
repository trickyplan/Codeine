<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'SEO',
            'ID' => 'Robots'
        ];

        $Sitemaps = F::Run('SEO.Sitemap', 'List Sitemap Indexes', $Call);

        foreach ($Sitemaps['Sitemap Indexes'] as $Sitemap)
            $Call['Output']['Content'][] = 'Sitemap: '.$Sitemap;

        if (isset($Call['Robots']['Crawl-delay']) && $Call['Robots']['Crawl-delay']>0)
            $Call['Output']['Content'][] = 'Crawl-delay: '.$Call['Robots']['Crawl-delay'];

        foreach ($Call['Robots']['Crawlers'] as $Crawler => $URL)
        {
            $Call['Output']['Content'][] = 'User-agent: '.$Crawler;
            $Call['Output']['Content'][] = 'Disallow: '.$URL;
        }

        $Call['Output']['Content'][] = 'User-agent: Yandex';
        $Call['Output']['Content'][] = 'Host: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'];

        $Call['Output']['Content'] = [implode(PHP_EOL, $Call['Output']['Content'])];
        return $Call;
    });