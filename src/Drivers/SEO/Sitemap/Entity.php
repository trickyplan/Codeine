<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Sitemap']['Links'] = [];

        $SubLinks = F::Run($Call['Entity'].'.Sitemap', 'Generate', $Call);

        foreach ($SubLinks as $Sublink)
            $Call['Sitemap']['Links'][] = [
                                  'url' =>
                                  [
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Call['Entities'][$Call['Entity']]['Frequency'],
                                      'priority'    => $Call['Entities'][$Call['Entity']]['Priority']
                                  ]
                               ];

        $Call['Output'] =  ['Root' => 'urlset', 'Content' => $Call['Sitemap']['Links']];

        return $Call;
    });