<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return (string) $Call['Value'];
    });

    setFn('Where', function ($Call)
    {
//        if (is_string($Call['Value']))
        if (is_scalar($Call['Value']))
            return (string) htmlspecialchars($Call['Value']);
        else
             return null;
    });

    setFn('Read', function ($Call)
    {
//        if (is_string($Call['Value']))
        if (is_scalar($Call['Value']))
            return (string) $Call['Value'];
        else
             return null;
    });
