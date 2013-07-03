<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] = 'Host: '.$Call['Project']['Hosts']['Production'];
        $Call['Output']['Content'][] = 'Sitemap: sitemap.xml';

        if (isset($Call['Robots']['Crawl-delay']))
            $Call['Output']['Content'][] = 'Crawl-delay: '.$Call['Robots']['Crawl-delay'];

        $Call['Output']['Content'][] = 'User-agent: *';
        $Call['Output']['Content'][] = 'Disallow: ';

        return $Call;
    });