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
            'ID' => 'Numbered'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        F::Run('Code.Run.Delayed', 'Run',
                    [
                        'Run' =>
                            [
                                'Service' => 'Parser.Numbered',
                                'Method' => 'Parse',
                                'Call' => $Call
                            ]
                    ]);

        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'alert alert-success',
                'Value' => 'Задание в очереди'
            ];

        return $Call;
    });

    setFn('Parse', function ($Call)
    {
        $Call['Pattern'] = $Call['Request']['Data']['Pattern'];
        $Call['To'] = $Call['Request']['Data']['To'];
        $Call['From'] = $Call['Request']['Data']['From'];

        $IDs = null;

        for ($Call['IX'] = $Call['From']; $Call['IX'] < $Call['To']; $Call['IX'] ++)
            $IDs[] = $Call['IX'];

        if (isset($Call['Randomize']))
            shuffle($IDs);

        if (is_array($IDs))
            foreach ($IDs as $Call['IX'])
            {
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

                if (empty($Result))
                    ;
                else
                {
                    if ($Call['Schema'] = F::Run('Parser', 'Discovery', $Call))
                    {
                        $Call = F::Run('Parser', 'Do', $Call, ['Markup' => $Result]);
                        $Slices = explode('.', $Call['Schema']);
                        $Call['Entity'] = array_pop($Slices);
                        $Call['Data']['Source'] = $Call['URL'];

                        if ($Call['Data']['Percent'] < 50)
                            ;
                        else
                            $Call['Data'] = F::Run('Entity', 'Create', $Call, ['One' => true]);
                    }
                }
            }

        return $Call;
    });