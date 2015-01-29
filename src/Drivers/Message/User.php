<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Inbox', function ($Call)
    {
        return F::Run ('Entity', 'Count',
            [
                'Entity' => 'Message',
                'Where' =>
                [
                    'Target' => $Call['Data']['ID']
                ]
            ]);
    });

    setFn('Outbox', function ($Call)
    {
        return F::Run ('Entity', 'Count',
            [
                'Entity' => 'Message',
                'Where' =>
                [
                    'User' => $Call['Data']['ID']
                ]
            ]);
    });