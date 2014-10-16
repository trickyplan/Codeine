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
        if (is_string($Call['Value']))
            return (string) htmlspecialchars($Call['Value']);
        else
             return null;
    });