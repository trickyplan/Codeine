<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = '';

        if ($Call['Page'] > 1)
            $Call['Value'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Paginator/Prev',
                    'Data' =>
                    [
                        'Num' => $Call['Page'] - 1,
                        'URL' => $Call['PageURL'] . ($Call['Page'] - 1) . $Call['PageURLPostfix'],
                    ]
                ]);

        for ($ic = $Call['Page'] - 7; $ic <= $Call['Page'] + 7; $ic++)
            if ($ic > 0 && $ic <= $Call['PageCount'])
            {
                if ($ic == 1)
                    $Call['Value'] .= F::Run('View', 'Load',
                        [
                            'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                            'ID' => ($ic == $Call['Page'] ? 'Paginator/Current' : 'Paginator/Page'),
                            'Data' =>
                            [
                                'Num' => $ic,
                                'URL' => $Call['FirstURL'] . $Call['PageURLPostfix']
                            ]
                        ]);
                else
                    $Call['Value'] .= F::Run('View', 'Load',
                        [
                            'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                            'ID' => ($ic == $Call['Page'] ? 'Paginator/Current' : 'Paginator/Page'),
                            'Data' =>
                            [
                                'Num' => $ic,
                                'URL' => $Call['PageURL'] . $ic . $Call['PageURLPostfix']
                            ]
                        ]);


            }

        if ($Call['Page'] < $Call['PageCount'])
            $Call['Value'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Paginator/Next',
                    'Data' =>
                    [
                        'Num' => $Call['Page'] + 1,
                        'URL' => $Call['PageURL'] . ($Call['Page'] + 1) . $Call['PageURLPostfix']
                    ]
                ]);

        return $Call;
    });
