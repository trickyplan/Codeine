<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        if (isset($Call['Data']['ID']))
            return F::Run(
                'Entity', 'Count', [
                    'Entity' => 'Comment',
                    'Where' =>
                    [
                        'Entity' => $Call['Entity'],
                        'Object' => $Call['Data']['ID']
                    ]
                ]
            );
        else
            return 0;
    });