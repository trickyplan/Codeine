<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.1'] = '404 Not Found';

        if (isset($Call['HTTP']['Referer']))
            F::Log('Page not found: '.$Call['HTTP']['URI']
                    .'.Referrer: '.$Call['HTTP']['Referer'], $Call['404 Error Level'], 'Developer');
        else
            F::Log('Page not found: '.$Call['HTTP']['URI'], $Call['404 Error Level'], 'Developer');

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