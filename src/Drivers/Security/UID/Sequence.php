<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        return F::Run('IO', 'Execute', $Call,
            [
                'Scope' => $Call['Entity'],
                'Execute' => 'ID'
            ]);
    });
