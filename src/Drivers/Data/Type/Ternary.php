<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if ($Call['Value'] == 0)
            return 0;
        else
            return (int) $Call['Value']/abs($Call['Value']);
    });

    setFn('Read', function ($Call)
    {
        return (int) $Call['Value'];
    });