<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.0'] = '404 Not Found';

        $Call['Page']['Title'] = '404';
        $Call['Page']['Description'] = 'TODO';
        $Call['Page']['Keywords'] = array ('TODO');

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

        $Call['Layouts'][] =
            [
                'Scope' => 'Errors',
                'ID' => '404'
            ];

        $Call['Failure'] = true;
        unset ($Call['Service'], $Call['Method']);

        return $Call;
     });

    setFn('Block', function ($Call)
    {
        $Call['Output']['Content'] = array (array (
                                            'Type'  => 'Template',
                                            'Scope' => 'Errors/Blocks',
                                            'ID' => '404'
                                        ));
        return $Call;
    });