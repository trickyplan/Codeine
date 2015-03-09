<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Parser',
            'ID' => 'Spider'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        F::Run('Spider', 'Do', $Call,
            [
                'Start' => $Call['Request']['Data']['Start'],
                'Scrape' =>
                [
                    'Whitelist' => ['http://market.yandex.ru/model.xml?modelid=7800733&hid=294661']
                ],
                'Spider' =>
                [
                    'Whitelist' => []
                ]
            ]);

        if ($Call['Schema'] = F::Run('Parser', 'Discovery', $Call))
        {
            $Call = F::Run('Parser', 'Do', $Call, ['Markup' => $Result]);
            $Slices = explode(DS, $Call['Schema']);
            $Call['Entity'] = array_pop($Slices);

            $Call['Data'] = F::Run('Entity', 'Create', $Call, ['One' => true]);
        }
        else
            $Call['Data'] = null;

        $Call['Output']['Content'][] =
        [
            'Type' => 'Block',
            'Value' => j($Call['Data'])
        ];

        return $Call;
    });