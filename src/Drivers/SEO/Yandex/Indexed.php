<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $YandexSERP = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => 'http://yandex.ru/yandsearch?text=host:'.$Call['Host']
            ]);

        $YandexSERP = array_pop($YandexSERP);

        d(__FILE__, __LINE__, $YandexSERP);


        $Call = F::Run('Parser', 'Do', $Call, [
            'Markup' => $YandexSERP,
            'Schema' => 'Yandex.SERP'
        ]);

        return $Call['Data']['Results'];
    });