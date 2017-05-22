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
            return filter_var($Call['Value'], FILTER_VALIDATE_BOOLEAN);
        else
            return false;
    });

    setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    setFn('Where', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return (bool) rand(0,1);
    });