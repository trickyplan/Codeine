<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        if (isset($_SERVER['HTTP_X_REAL_IP']))
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' && isset($Call['Pingback']))
        {
            $Pingback = file_get_contents($Call['Pingback']);
            mb_ereg('/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/', $Pingback, $Pockets);
            return $Pockets[0];
        }
        else
            return isset($Call['Substitute'][$_SERVER['REMOTE_ADDR']])? $Call['Substitute'][$_SERVER['REMOTE_ADDR']]: $_SERVER['REMOTE_ADDR'];
    });