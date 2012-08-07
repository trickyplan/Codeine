<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Get', function ($Call)
    {
        return $_SERVER['HTTP_USER_AGENT'];
    });