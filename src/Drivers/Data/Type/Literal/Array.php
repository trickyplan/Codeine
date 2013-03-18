<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (is_array($Call['Value']))
            return implode(';', $Call['Value']);
        else
            return $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        if (isset($Call['Purpose']) && $Call['Purpose'] != 'Read')
            return $Call['Value'];
        else
            return explode(';', $Call['Value']);
    });