<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return (string) $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return (string) $Call['Value'];
    });