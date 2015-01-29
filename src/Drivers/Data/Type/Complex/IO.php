<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        F::Run('IO', 'Write', $Call['Node'],
            [
                'Scope' => $Call['Entity'],
                'Where' => $Call['Data']['ID'],
                'Data'  =>
                [
                    $Call['Name'] => $Call['Value']
                ]
            ]);

        return null;
    });

    setFn(['Read','Where'], function ($Call)
    {
        return F::Run('IO', 'Read', $Call['Node'],
            [
                'Scope' => $Call['Entity'],
                'Where' => $Call['Data']['ID']
            ])[0][$Call['Name']];
    });