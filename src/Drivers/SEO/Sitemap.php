<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run('SEO.Sitemap.'.$Call['Sitemap']['Mode'], null, $Call);
    });

    setFn('Ping', function ($Call)
    {
        foreach($Call['SearchEngines'] as $Name=> $URL)
            $Call['Responses'][$Name] = F::Run('IO', 'Read',
                [
                     'Storage' => 'Web',
                     'ID' => $URL.'/ping?sitemap='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap.xml')
                ]);

        return $Call;
    });
