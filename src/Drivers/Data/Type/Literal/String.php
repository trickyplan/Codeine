<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return (string) filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    setFn('Read', function ($Call)
    {
        return (string) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return sha1(rand());
    });