<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Sitemap']['Mode']))
            $Call['Sitemap']['Mode'] = 'Index';

        return F::Run(null, $Call['Sitemap']['Mode'], $Call);
    });

    setFn('Index', function ($Call)
    {
        $Links = array();
        $Call['Headers']['Content-type:'] = 'text/xml; charset=utf-8';

        $Call['Renderer'] = 'View.XML';
        $Call['Namespace'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $Call['Attributes'] =
            array(
                array(
                    'Prefix' => 'xmlns',
                    'Key' => 'xsi',
                    'Value' => 'http://www.w3.org/2001/XMLSchema-instance'
                ),
                array(
                    'Prefix' => 'xsi',
                    'Key' => 'schemaLocation',
                    'Value' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
                )
            );

        foreach ($Call['Entities'] as $Handler => $Data)
            $Links[] =
                [
                    'sitemap' =>
                    [
                        'loc' => $Call['Host'].'/cache/sitemap/'.$Handler.'.xml'
                        // TODO lastmod
                    ]
                ];

        $Call['Output'] =  array('Root' => 'sitemapindex', 'Content' => $Links);

        return $Call;
    });

    setFn('Combined', function ($Call)
    {
        $Links = [];
        $Call['Headers']['Content-type:'] = 'text/xml; charset=utf-8';

        $Call['Renderer'] = 'View.XML';
        $Call['Namespace'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $Call['Attributes'] =
            array(
                array(
                    'Prefix' => 'xmlns',
                    'Key' => 'xsi',
                    'Value' => 'http://www.w3.org/2001/XMLSchema-instance'
                ),
                array(
                    'Prefix' => 'xsi',
                    'Key' => 'schemaLocation',
                    'Value' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
                )
            );
        foreach ($Call['Entities'] as $Handler => $Data)
        {
            $SubLinks = F::Run($Handler.'.Sitemap', 'Generate', $Call);
                foreach ($SubLinks as $Sublink)
                    $Links[] = array ('url' => array (
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Data['Frequency'],
                                      'priority'    => $Data['Priority']
                                  ));
        }

        $Call['Output'] =  array('Root' => 'urlset', 'Content' => $Links);

        return $Call;
    });

    setFn('Entity', function ($Call)
    {
        $Call['Links'] = [];
        $Call['Headers']['Content-type:'] = 'text/xml; charset=utf-8';

        $Call['Renderer'] = 'View.XML';
        $Call['Namespace'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $Call['Attributes'] =
            array(
                array(
                    'Prefix' => 'xmlns',
                    'Key' => 'xsi',
                    'Value' => 'http://www.w3.org/2001/XMLSchema-instance'
                ),
                array(
                    'Prefix' => 'xsi',
                    'Key' => 'schemaLocation',
                    'Value' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
                )
            );

        $SubLinks = F::Run($Call['Entity'].'.Sitemap', 'Generate', $Call);

        foreach ($SubLinks as $Sublink)
            $Call['Links'][] = array ('url' => array (
                                  'loc'         => htmlspecialchars($Sublink),
                                  'changefreq'  => $Call['Entities'][$Call['Entity']]['Frequency'],
                                  'priority'    => $Call['Entities'][$Call['Entity']]['Priority']
                              ));

        $Call['Output'] =  array('Root' => 'urlset', 'Content' => $Call['Links']);

        return $Call;
    });

    setFn('Ping', function ($Call)
    {
        foreach($Call['SearchEngines'] as $Name=> $URL)
            $Call['Responses'][$Name] = F::Run('IO', 'Read',
                array(
                     'Storage' => 'HTTP',
                     'ID' => $URL.'/ping?sitemap='.urlencode($Call['Host'].'/sitemap.xml')
                ));

        return $Call;
    });
