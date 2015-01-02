<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return strip_tags($Call['Value']); // FIXME
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return ($Call['Value']);
    });