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
        $Call['Pattern'] = $Call['Request']['Data']['Pattern'];
        $Call['To'] = $Call['Request']['Data']['To'];
        $Call['From'] = $Call['Request']['Data']['From'];

        F::Run(null, 'Parse', $Call);

        return $Call;
    });

    setFn('Parse', function ($Call)
    {
        $IDs = null;

        for ($Call['IX'] = $Call['From']; $Call['IX'] < $Call['To']; $Call['IX'] ++)
            $IDs[] = $Call['IX'];

        if (isset($Call['Randomize']))
            shuffle($IDs);

        if (is_array($IDs))
            foreach ($IDs as $ID)
            {
                $Call['IX'] = $ID;
                $Call['Data'] = [];
                $Call['URL'] = F::Live($Call['Pattern'], $Call);

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

                    if ($Call['Data']['Percent'] == 0)
                        ;
                    else
                        $Call['Data'] = F::Run('Entity', 'Create', $Call, ['One' => true]);
                }
                else
                    $Call['Data'] = null;
            }

        return $Call;
    });