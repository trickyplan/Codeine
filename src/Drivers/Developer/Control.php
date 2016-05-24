<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Developer',
            'ID' => 'Overview'
        ];

        $Call['Developer'] = F::loadOptions('Developer');

        if (isset($Call['Developer']['URL']))
        {
            $Call['Developer']['Meta'] =
                F::Run('IO', 'Read',
                    [
                        'Storage'       => 'Web',
                        'Format'        => 'Formats.JSON',
                        'Where'         =>
                        [
                            'ID' => $Call['Developer']['Host'].$Call['Developer']['Endpoint']
                        ]
                    ]);

            $Call['Developer']['Meta'] = array_pop($Call['Developer']['Meta']);
        }

        if (isset($Call['Project']['License']))
            $Call['License'] = F::Run('IO', 'Read',
                [
                    'Storage'   => 'Web',
                    'Format'    => 'Formats.JSON',
                    'IO One'    => true,
                    'Where'     => $Call['Developer']['Host'].'/license/'.$Call['Project']['License'].'.json'
                ]
            );

        if (isset($Call['License']) && is_array($Call['License']))
            foreach ($Call['License'] as $Product => $License)
            {
                $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Class' => $License['Expire'] > time()? 'alert alert-success': 'alert alert-danger',
                        'Value' => '<h2>'.$License['Host'].'</h2>'.'Действует до: <strong><datetime>'.$License['Expire'].'</datetime></strong>'
                    ];
            }
        else
            $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => 'Поддержка не оказывается.'
                    ];

        return $Call;
    });