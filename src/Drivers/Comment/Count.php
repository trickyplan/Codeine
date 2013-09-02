<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
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
    });