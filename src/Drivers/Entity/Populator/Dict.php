<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        $Dict =
            explode(PHP_EOL, F::Run('IO', 'Read', $Call, ['Storage' => 'Web', 'Where' =>
        'http://codeine-framework.ru/dicts/'.$Call['Key'].'.txt'])[0]);

        return $Dict;
    });