<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Get', function ($Call)
    {
        return $_SERVER['HTTP_USER_AGENT'];
    });