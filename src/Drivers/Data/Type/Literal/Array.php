<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (is_array($Call['Value']) && !empty($Call['Value']))
            return implode(';', $Call['Value']);
        else
            return $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        return explode(';', $Call['Value']);
    });

    setFn('Where', function ($Call)
    {
        return $Call['Value'];
    });