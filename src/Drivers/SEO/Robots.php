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
            $Call = F::Apply(null, 'Add.Sitemaps', $Call);
            $Call = F::Apply(null, 'Add.Host', $Call);
    
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
            $Call['Robots']['Directives'][] = 'User-agent: '.$Rule['User Agent'];
            if ($Rule['Allow'])
                $Call['Robots']['Directives'][] = 'Allow: '.$Rule['Path'].PHP_EOL;
            else
                $Call['Robots']['Directives'][] = 'Disallow: '.$Rule['Path'].PHP_EOL;
        }
        
        return $Call;
    });
    
    setFn('Add.Sitemaps', function ($Call)
    {
        $Sitemaps = F::Run('SEO.Sitemap', 'List Sitemap Indexes', $Call);
    
        foreach ($Sitemaps['Sitemap Indexes'] as $Sitemap)
            $Call['Robots']['Directives'][] = 'Sitemap: '.$Sitemap;
        
        return $Call;
    });
    
    setFn('Add.Host', function ($Call)
    {
        $Call['Robots']['Directives'][] = 'User-agent: Yandex';
        $Call['Robots']['Directives'][] = 'Host: '.$Call['HTTP']['Proto'].$Call['HTTP']['Host'];
        return $Call;
    });