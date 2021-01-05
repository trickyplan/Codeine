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

            $Call['Layouts'][] = ['Scope' => 'SEO','ID' => 'Robots'];
            
            $Call['Robots']['Directives'] = [];
            
            $Call = F::Apply(null, 'Add.Crawlers', $Call);
            $Call = F::Apply(null, 'Add.Host', $Call);
            $Call = F::Apply(null, 'Add.Sitemaps', $Call);

            $Call['Output']['Content'] = [implode(PHP_EOL,  $Call['Robots']['Directives'])];

        $Call = F::Hook('afterSEORobotsGenerate', $Call);

        return $Call;
    });

    setFn('Add.Crawlers', function ($Call)
    {
        if (isset($Call['Robots']['Crawl-delay']) && $Call['Robots']['Crawl-delay']>0)
            $Call['Robots']['Directives'][] = 'Crawl-delay: '.$Call['Robots']['Crawl-delay'];

        foreach ($Call['Robots']['Crawlers'] as $Rule)
        {
            if (isset($Crawlers[$Rule['User Agent']]))
                ;
            else
                $Crawlers[$Rule['User Agent']] = [];

            if ($Rule['Allow'])
                $Crawlers[$Rule['User Agent']][] = 'Allow: '.$Rule['Path'];
            else
                $Crawlers[$Rule['User Agent']][] = 'Disallow: '.$Rule['Path'];
        }

        foreach ($Crawlers as $CrawlerName => $CrawlerDirectives)
        {
            $Call['Robots']['Directives'][] = 'User-agent: '.$CrawlerName;
            $Call['Robots']['Directives'][] = implode(PHP_EOL, $CrawlerDirectives);
            $Call['Robots']['Directives'][] = '';
        }

        return $Call;
    });

    setFn('Add.Sitemaps', function ($Call)
    {
        $Call['Robots']['Directives'][] = '';
        $Sitemaps = F::Run('SEO.Sitemap', 'List.Sitemaps.RobotsTxt', $Call);

        if (empty($Sitemaps))
            ;
        else
            foreach ($Sitemaps as $Sitemap)
                $Call['Robots']['Directives'][] = 'Sitemap: '.$Sitemap;
        
        return $Call;
    });
    
    setFn('Add.Host', function ($Call)
    {
        $Call['Robots']['Directives'][] = 'User-agent: Yandex';
        $Call['Robots']['Directives'][] = 'Host: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'];
        return $Call;
    });