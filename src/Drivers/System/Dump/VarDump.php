<?php

    /* Codeine
     * @author BreathLess
     * @description: var_dump wrapper
     * @package Codeine
     * @version 7.2
     * @date 22.11.10
     * @time 5:21
     */

    self::setFn('Variable', function ($Call)
    {
        var_dump($Call['Value']);
    });
