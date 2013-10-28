<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Apply(null, $Call['HTTP Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Response = (array) json_decode(F::Run('IO', 'Write',
        [
            'Storage' => 'Web',
            'Where'   => 'https://api.paysio.com/v1/charges',
            'User' => '5NglzvkvxQaJt4TEoVxBAW092dKavPFEk0Uq9De',
            'Pass' => '',
            'Data' =>
            [
                'amount' => 100000,
                'currency_id' => 'rur',
                'description' => 'Test charge'
            ]
        ]), false);
        d(__FILE__, __LINE__, $Response);
        header('Location: https://paysio.com/v1/charges/'.$Response['id'].'/invoice');
        return $Call;
    });