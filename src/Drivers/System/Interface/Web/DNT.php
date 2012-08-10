<?php

/* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Detect', function ($Call)
    {
        return (isset($_SERVER['HTTP_DNT']) || isset($_SERVER['X-Do-Not-Track']));
     });