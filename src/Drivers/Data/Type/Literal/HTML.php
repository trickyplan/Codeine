<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return htmlspecialchars($Call['Value']); // FIXME
    });

    setFn('Read', function ($Call)
    {
        return htmlspecialchars_decode($Call['Value']);
    });