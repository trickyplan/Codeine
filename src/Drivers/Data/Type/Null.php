<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return null;
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });