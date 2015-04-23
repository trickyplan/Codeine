<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $GoogleSERP = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => 'https://www.google.com/search?q=site:' . $Call['Host']
            ]);

        $GoogleSERP = array_pop($GoogleSERP);

        $Call = F::Run('Parser', 'Do', $Call, [
            'Markup' => $GoogleSERP,
            'Schema' => 'Google.SERP'
        ]);

        return $Call['Data']['Results'];
    });