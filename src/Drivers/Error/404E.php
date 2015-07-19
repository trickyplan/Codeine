<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['Layouts'] = [
            [
                'Scope' => 'Default',
                'ID' => 'Main'
            ],
            [
                'Scope' => 'Project',
                'ID' => 'Zone'
            ]
        ];

        if (isset($Call['Entity']))
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => '404E'
                ];

        $Call['Layouts'][] =
            [
                'Scope' => 'Error',
                'ID' => '404E'
            ];

        $Call['Failure'] = true;
        unset ($Call['Service'], $Call['Method']);

        return $Call;
     });

    setFn('Block', function ($Call)
    {
        $Call['Output']['Content'] = [
            [
                'Type'  => 'Template',
                'Scope' => 'Error/Blocks',
                'ID' => '404'
            ]];
        return $Call;
    });