<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeSEORobotsGenerate', $Call);

        $Call['Layouts'][] =
        [
            'Scope' => 'SEO',
            'ID' => 'Robots'
        ];

        if (F::Dot($Call, 'SEO.Robots.Domain Is Valid')) {
            $Sitemaps = F::Run('SEO.Sitemap', 'List Sitemap Indexes', $Call);

            foreach ($Sitemaps['Sitemap Indexes'] as $Sitemap)
                $Call['Output']['Content'][] = 'Sitemap: '.$Sitemap;

            if (isset($Call['Robots']['Crawl-delay']) && $Call['Robots']['Crawl-delay']>0)
                $Call['Output']['Content'][] = 'Crawl-delay: '.$Call['Robots']['Crawl-delay'];

            foreach ($Call['Robots']['Crawlers'] as $Crawler => $URL)
            {
                $Call['Output']['Content'][] = 'User-agent: '.$Crawler;
                $Call['Output']['Content'][] = 'Disallow: '.$URL.PHP_EOL;
            }

            $Call['Output']['Content'][] = 'User-agent: Yandex';
            $Call['Output']['Content'][] = 'Host: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'];

        } else {
            $Call['Output']['Content'][] = 'User-agent: *';
            $Call['Output']['Content'][] = 'Disallow: /';
        }

        $Call['Output']['Content'] = [implode(PHP_EOL, $Call['Output']['Content'])];

        $Call = F::Hook('afterSEORobotsGenerate', $Call);

        return $Call;
    });