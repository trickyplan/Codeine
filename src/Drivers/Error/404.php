<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.1'] = '404 Not Found';

        if (isset($Call['HTTP']['Referer']))
            F::Log('Page not found: '.$Call['HTTP']['URI']
                    .'.Referrer: '.$Call['HTTP']['Referer'], $Call['Error']['404']['Level'], 'Marketing');
        else
            F::Log('Page not found: '.$Call['HTTP']['URI'], $Call['Error']['404']['Level'], 'Marketing');

        $Call['Layouts'] = [
            [
                'Scope'     => 'Default',
                'ID'        => 'Main',
                'Context'   => $Call['Context']
            ],
            [
                'Scope' => 'Project',
                'ID' => 'Zone',
                'Context'   => $Call['Context']
            ]
        ];

        if (isset($Call['Entity']))
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => '404',
                    'Context'   => $Call['Context']
                ];

        $Call['Layouts'][] =
            [
                'Scope' => 'Error',
                'ID' => '404',
                'Context'   => $Call['Context']
            ];

        $Call['Output']['Content'][] = 404;

        $Call['Failure'] = true;
        unset ($Call['Service'], $Call['Method']);

        return $Call;
     });