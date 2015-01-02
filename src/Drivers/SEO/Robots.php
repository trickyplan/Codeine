<?php

    /* Codeine
     * @author BreathLess
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

        $Call['Output']['Content'][] = 'Host: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'];
        $Call['Output']['Content'][] = 'Sitemap: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap.xml';

        if (isset($Call['Robots']['Crawl-delay']))
            $Call['Output']['Content'][] = 'Crawl-delay: '.$Call['Robots']['Crawl-delay'];

        foreach ($Call['Robots']['Crawlers'] as $Crawler => $URL)
        {
            $Call['Output']['Content'][] = 'User-agent: '.$Crawler;
            $Call['Output']['Content'][] = 'Disallow: '.$URL;
        }

        $Call['Output']['Content'] = [implode(PHP_EOL, $Call['Output']['Content'])];
        return $Call;
    });