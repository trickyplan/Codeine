<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.0'] = '403 Forbidden';

        $Call['Page']['Title'] = '403';
        $Call['Page']['Description'] = 'TODO';
        $Call['Page']['Keywords'] = array ('TODO');

        $Call['Run'] = '/403';

        if (isset($Call['Reason']))
            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'Errors/403',
                'ID' => $Call['Reason']
            ];
        else
            $Call['Output']['Content'][] =
            [
                'Type' => 'Template',
                'Scope' => 'Errors',
                'ID' => '403'
            ];

        return $Call;
     });

    setFn('Block', function ($Call)
    {
        $Call['Output']['Content'] = [
                                        [
                                            'Type'  => 'Template',
                                            'Scope' => 'Errors/Blocks',
                                            'ID' => '403',
                                            'Data'  => $Call
                                        ]
                                     ];
        return $Call;
    });

    setFn('Die', function ($Call)
    {
        header('HTTP/1.0 403 Forbidden');
        readfile(F::findFile('Assets/Errors/403.html'));
        die();
    });