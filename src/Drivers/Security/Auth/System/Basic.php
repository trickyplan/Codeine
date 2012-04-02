<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Get', function ($Call)
    {
        return isset($_SERVER['PHP_AUTH_USER'])? $_SERVER['PHP_AUTH_USER']: 'Guest';
    });