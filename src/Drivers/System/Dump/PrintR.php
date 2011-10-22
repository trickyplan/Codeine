<?php

    /* Codeine
     * @author BreathLess
     * @description: print_r wrapper
     * @package Codeine
     * @version 6.0
     * @date 22.11.10
     * @time 5:21
     */

    self::Fn('Variable', function ($Call)
    {
        print_r($Call['Value']);
    });
