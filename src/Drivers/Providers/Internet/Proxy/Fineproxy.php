<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('List', function ($Call)
    {
        $List = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where'   =>
                [
                    'ID' => $Call['Fineproxy']['List URL']
                ],
                'Data'  =>
                [
                    'format' => $Call['Fineproxy']['Format'],
                    'type' => $Call['Fineproxy']['Type'],
                    'login' => $Call['Fineproxy']['Login'],
                    'password' => $Call['Fineproxy']['Password']
                ]
            ]);

        $List = array_pop($List);
        $List = explode(PHP_EOL, $List);

        foreach ($List as &$Proxy)
            $Proxy = trim($Proxy);

        return $List;
    });