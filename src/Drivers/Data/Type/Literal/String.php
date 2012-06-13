<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return (string) filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    self::setFn('Read', function ($Call)
    {
        return (string) $Call['Value'];
    });