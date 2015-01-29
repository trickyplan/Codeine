<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Random integer
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        return $Call['Session']['User']['ID'].F::Run('IO', 'Execute', $Call,
            [
                'Scope'   => $Call['Entity'],
                'Execute' => 'ID',
                'Where'   =>
                [
                    'User' => $Call['Session']['User']['ID']
                ]
            ]);
    });
