<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Value']))
            return (bool) $Call['Value'];
        else
            return false;
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return (bool) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return (bool) rand(0,1);
    });