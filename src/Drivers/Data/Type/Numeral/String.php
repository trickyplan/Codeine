<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Write', function ($Call)
    {
        return (string) filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    self::setFn('Read', function ($Call)
    {
        return (string) $Call['Value'];
    });

    self::setFn('Populate', function ($Call)
    {
        $Result = '';

        for($ic = 0; $ic < $Call['Node']['Size']; $ic++)
            $Result.= rand(0,9);

        return $Result;
    });