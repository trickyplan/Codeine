<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Decode', function ($Call)
    {
        return igbinary_unserialize($Call['Value']);
    });

    setFn('Encode', function ($Call)
    {
        return igbinary_serialize($Call['Value']);
    });