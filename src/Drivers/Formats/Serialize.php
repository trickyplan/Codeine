<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     */

    setFn('Decode', function ($Call)
    {
        return unserialize($Call['Value']);
    });

    setFn('Encode', function ($Call)
    {
        return serialize($Call['Value']);
    });