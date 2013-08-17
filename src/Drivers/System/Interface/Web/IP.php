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
            preg_match('/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/', $Pingback, $Pockets);
            $IP = $Pockets[0];
            F::Log('Pingback IP: *'.$IP.'* from *'.$Call['Pingback'].'*', LOG_INFO);
        }
        else
        {
            if (isset($Call['Substitute'][$_SERVER['REMOTE_ADDR']]))
            {
                $IP = $Call['Substitute'][$_SERVER['REMOTE_ADDR']];
                F::Log('IP substituted from *'.$_SERVER['REMOTE_ADDR'].'* to '.$IP, LOG_INFO);
            }
            else
                $IP = $_SERVER['REMOTE_ADDR'];
        }

        return $IP;
    });