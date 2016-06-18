<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return (array) $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        return (array) $Call['Value'];
    });

    setFn('Where', function ($Call)
    {
        return $Call['Value'];
    });