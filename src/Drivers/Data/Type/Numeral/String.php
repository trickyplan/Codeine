<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return (string) filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return (string) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        $Result = '';

        for($ic = 0; $ic < $Call['Node']['Size']; $ic++)
            $Result.= rand(0,9);

        return $Result;
    });