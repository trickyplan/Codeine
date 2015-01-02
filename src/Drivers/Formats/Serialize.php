<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        return unserialize($Call['Value']);
    });

    setFn('Write', function ($Call)
    {
        return serialize($Call['Value']);
    });