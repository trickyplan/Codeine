<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        $Links = array();
        $Call['Headers']['Content-type:'] = 'text/xml; charset=utf-8';

        foreach($Call['Handlers'] as $Handler)
        {
            $SubLinks = F::Live($Handler['Source']);

            foreach ($SubLinks as $Sublink)
                $Links[] = array ('url' => array (
                                      'loc'         => htmlspecialchars($Sublink),
                                      'changefreq'  => $Handler['Frequency'],
                                      'priority'    => $Handler['Priority']
                                  ));

        }

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

        $Call['Output'] =  array('Root' => 'urlset', 'Content' => $Links);

        return $Call;
    });

    setFn('Ping', function ($Call)
    {
        foreach($Call['SearchEngines'] as $Name=> $URL)
            $Call['Responses'][$Name] = F::Run('IO', 'Read',
                array(
                     'Storage' => 'HTTP',
                     'ID' => $URL.'/ping?sitemap='.urlencode($_SERVER['HTTP_HOST'].'/sitemap.xml')
                ));

        return $Call;
    });