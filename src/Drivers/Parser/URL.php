<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
            'ID' => 'URL'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call['URL'] = $Call['Request']['Data']['URL'];

        if ($Call['Schema'] = F::Run('Parser', 'Discovery', $Call))
        {
            $Schema = F::loadOptions('Parser/'.$Call['Schema']);
            $Call = F::Merge($Call, $Schema);

            $Result = F::Live($Call['Parser']['URL']['Backend'],
            [
                 'Where' =>
                [
                    'ID' => $Call['URL']
                ]
            ]);

            d(__FILE__, __LINE__, $Result);
            $Result = array_pop($Result);

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