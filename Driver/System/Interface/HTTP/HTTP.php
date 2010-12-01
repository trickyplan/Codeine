<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: HTTP Interface
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 17.11.10
     * @time 16:37
     */

    self::Fn('Detect', function ($Call)
    {
        return isset($_SERVER['REQUEST_URI']);
    });

    self::Fn('Input', function ($Call)
    {
        header('Content-type: text/html;charset=utf-8;');
        
        if (isset($_SERVER['REQUEST_URI']))
            return $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'];
        else
            return null;
    });