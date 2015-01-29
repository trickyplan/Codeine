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

    setFn(['Read', 'Where'], function ($Call)
    {
        return (string) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return rand().'@codeine-framework.ru';
    });