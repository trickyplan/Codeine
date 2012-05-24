<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Widget', function ($Call)
    {
        return $Call['Widgets'];
    });