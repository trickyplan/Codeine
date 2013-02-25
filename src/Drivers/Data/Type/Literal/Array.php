<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return implode(';', $Call['Value']);
    });

    setFn('Read', function ($Call)
    {
        return explode(';', $Call['Value']);
    });