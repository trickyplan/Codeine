<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Parser/Control',
            'ID' => 'URL'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['Request']);
        return $Call;
    });