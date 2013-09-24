<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] = 'Host: '.$Call['Host'];
        $Call['Output']['Content'][] = 'Sitemap: '.$Call['Host'].'/sitemap.xml';

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