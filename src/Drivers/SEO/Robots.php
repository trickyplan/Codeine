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

        $Call['Output']['Content'][] = 'User-agent: *';
        $Call['Output']['Content'][] = 'Disallow: ';

        $Call['Output']['Content'] = [implode(PHP_EOL, $Call['Output']['Content'])];
        return $Call;
    });