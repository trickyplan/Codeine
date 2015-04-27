<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Convert', function ($Call)
     {
         $Sitemap = F::Run('IO', 'Read',
             [
                 'Storage' => 'Web',
                 'Format'  => 'Formats.XML',
                 'Where'   => $Call['Sitemap'],
                 'One'     => true
             ]);

         foreach ($Sitemap['url'] as $URL)
             echo $URL['loc'].PHP_EOL;
     });