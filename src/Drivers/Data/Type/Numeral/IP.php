<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return inet_pton($Call['Value']);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        if (is_int($Call['Value']))
            return inet_ntop($Call['Value']);
        else
            return null;
    });

    setFn('Populate', function ($Call)
    {
        return inet_ntop(rand());
    });