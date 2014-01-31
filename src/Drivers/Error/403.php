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

        $Call['Run'] = '/403';

        if (isset($Call['Reason']))
            ;
        else
            $Call['Reason'] = 'Access';

        $Call['Output']['Content'][] =
        [
            'Type' => 'Template',
            'Scope' => 'Errors/403',
            'ID' => $Call['Reason']
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