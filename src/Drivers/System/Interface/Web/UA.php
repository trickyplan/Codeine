<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Get', function ($Call)
    {
        return $_SERVER['HTTP_USER_AGENT'];
    });