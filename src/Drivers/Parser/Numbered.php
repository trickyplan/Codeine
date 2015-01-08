<?php

    /* Codeine
     * @author BreathLess
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
            'ID' => 'Numbered'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        for ($Call['IX'] = $Call['Request']['Data']['From']; $Call['IX'] < $Call['Request']['Data']['To']; $Call['IX'] ++)
        {
            $Call['Data'] = [];
            $Call['URL'] = F::Live($Call['Request']['Data']['Pattern'], $Call);

            $Result = F::Live($Call['Parser']['Numbered']['Backend'],
            [
                'Where' =>
                [
                    'ID' => $Call['URL']
                ]
            ]);

            $Result = array_pop($Result);

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
        }


        return $Call;
    });