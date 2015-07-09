<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (string) $Value;

        return $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (string) $Value;

        return $Call['Value'];
    });

    setFn('Where', function ($Call)
    {
        return $Call['Value'];
    });