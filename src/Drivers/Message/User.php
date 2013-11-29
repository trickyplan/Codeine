<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Inbox', function ($Call)
    {
        return F::Run ('Entity', 'Count',
            [
                'Entity' => 'Message',
                'Where' =>
                [
                    'Target' => $Call['Current']['ID']
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
                    'User' => $Call['Current']['ID']
                ]
            ]);
    });