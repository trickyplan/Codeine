<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Value']))
            return (bool) $Call['Value'];
        else
            return false;
    });

    setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });