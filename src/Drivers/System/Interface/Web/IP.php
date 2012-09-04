<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Get', function ($Call)
    {
        if (isset($_SERVER['HTTP_X_REAL_IP']))
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' && isset($Call['Pingback']))
            return file_get_contents($Call['Pingback']);
        else
            return isset($Call['Substitute'][$_SERVER['REMOTE_ADDR']])? $Call['Substitute'][$_SERVER['REMOTE_ADDR']]: $_SERVER['REMOTE_ADDR'];
    });