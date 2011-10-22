<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 1:35
     */

    self::Fn('Detect', function ($Call)
    {
        return isset($_SERVER['REQUEST_URI']);
    });

    self::Fn('Get', function ($Call)
    {
        return $_SERVER['REQUEST_URI'];
    });
