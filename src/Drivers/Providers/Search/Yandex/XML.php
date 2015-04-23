<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
          $Result = F::Run('IO', 'Read',
                [
                    'Storage' => 'Web',
                    'Format' => 'Formats.XML',
                    'Where'   => 'https://xmlsearch.yandex.ru/xmlsearch',
                    'Data'    =>
                    [
                        'user' => $Call['Yandex']['XML']['User'],
                        'key'  => $Call['Yandex']['XML']['Key'],
                        'l10n' => $Call['Yandex']['XML']['Locale'],
                        'sortby' => $Call['Yandex']['XML']['Sort'],
                        'filter' => $Call['Yandex']['XML']['Filter'],
                        'query'  => $Call['Query']
                    ]
                ]);

         return $Result[0]['response']['found'][2];
     });