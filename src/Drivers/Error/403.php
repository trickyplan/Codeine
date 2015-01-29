<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.1'] = '403 Forbidden';

        $Call['Run'] = '/403';

        if (isset($Call['Reason']))
            ;
        else
            $Call['Reason'] = 'Access';

        $Call['Output']['Content'] =
        [
            [
                'Type' => 'Template',
                'Scope' => 'Error/403',
                'ID' => $Call['Reason']
            ]
        ];

        return $Call;
     });

    setFn('Block', function ($Call)
    {
        $Call['Run'] = '/403';
        $Call['Output']['Content'] = [
                                        [
                                            'Type'  => 'Template',
                                            'Scope' => 'Error/Blocks',
                                            'ID' => '403',
                                            'Data'  => $Call
                                        ]
                                     ];
        return $Call;
    });